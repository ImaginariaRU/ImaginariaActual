var ls = ls || {};

ls.ignore = (function ($) {
    
	var aIgnoreComments=[];
    
    this.updateIgnore = function(obj, idUser){
        var valueArray = new Array();
        $.each($('#ignore_setting_form input:checkbox:checked'), function() {
            valueArray.push($(this).val());
        });
        $('#ignore_setting_form').children().each(function(i, item){$(item).attr('disabled','disabled')});
        var reason = $('#ignore_setting_reason').val();
        var rating = $('#ignore_comment_rating').val();
        var profileshow = $('#ignore_setting_form input:radio:checked').val();
        var url = aRouter.ajax+'ignore/setting/';
        var params = {idUser: idUser, data: valueArray.join('|'), reason: reason, rating: rating, profileshow: profileshow};
        
        ls.ajax(url, params, function(result){
            $('#ignore_setting_form').children().each(function(i, item){$(item).removeAttr('disabled')});
            if (!result) {
                ls.msg.error('Error','Please try again later');
            }
            if (result.bStateError) {
                ls.msg.error(null,result.sMsg);
            } else {
                ls.msg.notice(null,result.sMsg);
                $('#ignore_setting_form').jqmHide();
            }
        });
        return false;
    };

	this.showIgnore = function(obj, idUser){
        var url = aRouter.ajax+'ignore/window/';
		var params = {idUser: idUser};

		ls.ajax(url, params, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
                $('#ignore_setting_form').remove();
                $('body').prepend(result.sText);
				$('#ignore_setting_form').jqm();
				$('#ignore_setting_form').jqmShow();
			}
		}.bind(this));
		return false;
	};
    
	this.deleteIgnore = function(obj, idUser, name){
        if (!confirm('Удалить?'))
            return false;
        var url = aRouter.ajax+'ignore/setting/';
		var params = {idUser: idUser, data: '', reason: ''};
        $(name).hide();
		ls.ajax(url, params, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
                $(name).show();
			} else {
				ls.msg.notice(null,result.sMsg);
			}
		});
		return false;
	};
    
    this.IgnorePostMyTopic = function() {
        $('#comments').find('a.reply-link').parent().addClass('js-title-ignore').attr('title', ls.lang.get('plugin.ignore.js_ignore_post_comment_my_topic')).html('<a href="#comments" class="ignore-link-reply">Ответить</a>');
        $('#comment_id_0').hide();
        $('#reply').hide();
        $('#reply').after('<div class="system-message-error">'+ls.lang.get('plugin.ignore.js_ignore_post_comment_my_topic')+'</div>');
    }

    this.HideMeComments = function() {
        $.each($('li.ignore-hide-comment'), function() {
            var id = $(this).attr('id').substr(20);
            var classes = $('#comment_id_'+id).attr('class');
            var comment = $('#comment_id_'+id).html();
            var author = $('#comment_id_'+id+' li.comment-author').html();
            var date = $('#comment_id_'+id+' li.comment-date').html();
            var vote = $('#vote_total_comment_'+id).text();
            var user_id = parseInt( $('#comment_id_'+id+' .ignore-comment-user-id').text() );
            if (user_id) {
                var newArray = [id, comment, user_id, classes, author];
                aIgnoreComments.push(newArray);
                $('#comment_id_'+id+' ul').hide();
                $('#comment_id_'+id).removeClass('comment-self comment-new comment-bad').addClass('ignore-comment');
                $('#comment_content_id_'+id).css('margin: 0;padding: 0;');
                $('#comment_content_id_'+id).html('<ul class="comment-ignore-info"><li>Игнор: <b>'+author+'</b></li><li>'+date+'</li><li class="item"><a href="#" onclick="ls.ignore.IgnoreShowComment('+"'comment', "+id+'); return false;">Показать</a> | <a href="#" onclick="ls.ignore.IgnoreShowComment('+"'user', "+user_id+'); return false;">Все</a></li><li class="vote">'+vote+'</li></ul>');
            }
        });
        var count = aIgnoreComments.length;
        if (count) {
            ls.ignore.IgnoreShowCommentList();
            $('#ignore-comment-list-count').text(count);
        }
    }

    this.IgnoreShowComment = function(by, id) {
        var newArray = [];
        for (var j = 0; j < aIgnoreComments.length; j++) {
            if (by == 'all' || (by == 'comment' && aIgnoreComments[j][0] == id) || (by == 'user' && aIgnoreComments[j][2] == id)) {
                $('#comment_id_'+aIgnoreComments[j][0]).html(aIgnoreComments[j][1]).removeClass('ignore-comment').attr('class', aIgnoreComments[j][3]);
            } else {
                newArray.push(aIgnoreComments[j]);
            }
        }
        aIgnoreComments = newArray;
        newArray = [];
        var count = aIgnoreComments.length;
        if (count) {
            $('#ignore-comment-list-count').text(count);
        } else {
            $('.ignore-comment-list').remove();
        }
    }

    this.IgnoreShowCommentList = function() {
        $('.comments-header').append('<div class="ignore-comment-list"><span id="ignore-comment-list-count">0</span> '+ls.lang.get('plugin.ignore.js_ignore_block_comments')+'<div><a href="#" onclick="ls.ignore.IgnoreShowComment('+"'all', "+'0); return false;">Показать все</a></div></div>');
    }

    this.IgnoreRyplyMyComment = function() {
	   $.each($('li.ignore-reply-comment'), function() {
	       $('#comment_id_'+$(this).attr('id').substr(21)).find('a.reply-link').parent().addClass('js-title-ignore').attr('title', ls.lang.get('plugin.ignore.js_ignore_reply_my_comment')).html('<a href="#comment'+$(this).attr('id').substr(21)+'" class="ignore-link-reply">Ответить</a>');
	   });
    }

    this.IgnorePostMyWall = function() {
        $('.wall-submit-reply').remove();
        $('.wall-submit').hide();
        $('.wall-submit').after('<div class="system-message-error">'+ls.lang.get('plugin.ignore.js_ignore_post_my_wall')+'</div>');
        $('#wall-note-list-empty').hide();
        $('#wall-container').find('.wall-item-actions').hide();
    }
    
    this.updateIgnoreTarget = function(obj, iTargetId, sTargetType, idUser){
        var valueArray = new Array();
        $.each($('#ignore_target_form input:checkbox:checked'), function() {
            valueArray.push($(this).val());
        });
        $('#ignore_target_form').children().each(function(i, item){$(item).attr('disabled','disabled')});
        var sLogin = '';
        if (!idUser)
            sLogin = $('#ignore_target_form_login').val();
        var reason = $('#ignore_target_form_reason').val();
        var url = aRouter.ajax+'ignore/target/';
        var params = {iTargetId: iTargetId, sTargetType: sTargetType, idUser: idUser, sLogin: sLogin, data: valueArray.join('|'), reason: reason};
        
        ls.ajax(url, params, function(result){
            $('#ignore_target_form').children().each(function(i, item){$(item).removeAttr('disabled')});
            if (!result) {
                ls.msg.error('Error','Please try again later');
            }
            if (result.bStateError) {
                ls.msg.error(null,result.sMsg);
            } else {
                ls.msg.notice(null,result.sMsg);
                $('#ignore_target_form').jqmHide();
                $('#ignore-target-block p.title span').html(result.iCount);
                if (result.iCount) {
                    $('#ignore-target-block p.users').html(result.sUsers).show();
                } else {
                    $('#ignore-target-block p.users').html('').hide();
                }
            }
        });
        return false;
    };

	this.showIgnoreTarget = function(obj, iTargetId, sTargetType, idUser){
        var url = aRouter.ajax+'ignore/target/window/';
		var params = {iTargetId: iTargetId, sTargetType: sTargetType, idUser: idUser};

		ls.ajax(url, params, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
                $('#ignore_target_form').remove();
                $('body').prepend(result.sText);
				$('#ignore_target_form').jqm();
				$('#ignore_target_form').jqmShow();
			}
		}.bind(this));
		return false;
	};
    
	return this;
}).call(ls.ignore || {},jQuery);

jQuery(document).ready(function($){
    ls.ignore.IgnoreRyplyMyComment();
    ls.ignore.HideMeComments();
	$('.js-title-ignore').poshytip({
		className: 'infobox-standart',
		alignTo: 'target',
		alignX: 'right',
		alignY: 'center',
		offsetX: 10,
		liveEvents: true,
		showTimeout: 500
	});

});
