/**
 * Created by koco on 1/16/2017.
 */
(function ($) {
  var I18nContent = function () {
    this.$toggleBtn = $('.togglebutton > label > input');

    this.init();
  };

  I18nContent.prototype = {
    constructor: I18nContent,

    init: function () {
      this.toggleStatus();
    },


    toggleStatus: function () {
      var me = this;
      this.$toggleBtn.change(function() {
        var $row = $(this).closest('tr');
        var action = $row.data('toggle-action') || 'toggle-status';
        me._post(action, {id: $row.data('key')}).done(function (res) {
          if(res.success) {

          }else{
            alert(res.msg);
          }
        });

      });
    },


    _get: function (url, data) {
      return $.ajax({
        url: url,
        type: 'GET',
        data: data,
        dataType: 'json'
      });
    },

    _post: function (url, data) {
      return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json'
      });
    }


  };


  window.i18ncontent = new I18nContent();
})($);