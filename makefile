#!/usr/bin/make
# SHELL = bash
PROJECT_NAME  = imaginaria
PROJECT_DIR = imaginaria
PROJECT_PATH = $(DESTDIR)/var/www/$(PROJECT_DIR)
PATH_MANTICONF_INDEXES = /etc/manticonf/conf.d/imaginaria

help:
	@perl -e '$(HELP_ACTION)' $(MAKEFILE_LIST)

install: 	##@system Install package. Don't run it manually!!!
	@echo Installing...
	install -d $(PROJECT_PATH)
	cp -r admin.cron $(PROJECT_PATH)
	cp -r admin.tools $(PROJECT_PATH)
	cp -r config $(PROJECT_PATH)
	cp -r engine $(PROJECT_PATH)
	cp -r plugins $(PROJECT_PATH)
	cp -r templates $(PROJECT_PATH)
	cp composer.json $(PROJECT_PATH)
	cp index.php $(PROJECT_PATH)
	cp favicon.ico $(PROJECT_PATH)
	cp registration.html $(PROJECT_PATH)
	cp registration_ajax_callback.php $(PROJECT_PATH)
	cp vote_view.php $(PROJECT_PATH)
	git rev-parse --short HEAD > $(PROJECT_PATH)/_version
	git log --oneline --format=%B -n 1 HEAD | head -n 1 >> $(PROJECT_PATH)/_version
	git log --oneline --format="%at" -n 1 HEAD | xargs -I{} date -d @{} +%Y-%m-%d >> $(PROJECT_PATH)/_version
	cd $(PROJECT_PATH)/ && composer install && rm composer.lock
	cp makefile.production-toolkit $(PROJECT_PATH)/makefile
	mkdir -p $(DESTDIR)$(PATH_MANTICONF_INDEXES)
	cp -r config/searchd/* $(DESTDIR)$(PATH_MANTICONF_INDEXES)/
	chmod -R 0644 $(DESTDIR)$(PATH_MANTICONF_INDEXES)/
	install -d $(PROJECT_PATH)/logs
	install -d $(PROJECT_PATH)/cache
	install -d $(PROJECT_PATH)/tmp
	install -d /var/tmp/imaginaria

update:		##@build Update project from GIT
	@echo Updating project from GIT
	git pull --no-rebase

build:		##@build Build project to DEB Package
	@echo Building project to DEB-package
	export COMPOSER_HOME=/tmp/ && dpkg-buildpackage -rfakeroot --no-sign

dchr:		##@development Publish release
	@dch --controlmaint --release --distribution unstable

dchv:		##@development Append release
	@export DEBEMAIL="karel.wintersky@gmail.com" && \
	export DEBFULLNAME="Karel Wintersky" && \
	echo "$(YELLOW)------------------ Previous version header: ------------------$(GREEN)" && \
	head -n 3 debian/changelog && \
	echo "$(YELLOW)--------------------------------------------------------------$(RESET)" && \
	read -p "Next version: " VERSION && \
	dch --controlmaint -v $$VERSION

# ------------------------------------------------
# Add the following 'help' target to your makefile, add help text after each target name starting with '\#\#'
# A category can be added with @category
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)
HELP_ACTION = \
	%help; while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-_]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
	print "usage: make [target]\n\n"; for (sort keys %help) { print "${WHITE}$$_:${RESET}\n"; \
	for (@{$$help{$$_}}) { $$sep = " " x (32 - length $$_->[0]); print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; }; \
	print "\n"; }

# -eof-

