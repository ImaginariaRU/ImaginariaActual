# Crontab file for imaginaria

# Every 15 mins generate sitemap
# */15    *       *       *       *       www-data        /srv/imaginaria.sitemap-generate.sh 2>&1

# Regenerate indexes

# */17    *       *       *       *       manticore       indexer --rotate topicsIndex >>   /var/log/manticore/rotate_topics.log 2>&1
# */5     *       *       *       *       manticore       indexer --rotate commentsIndex >> /var/log/manticore/rotate_comments.log 2>&1

# EOF
