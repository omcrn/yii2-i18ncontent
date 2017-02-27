/**
 * Created by koco on 1/16/2017.
 */
(function ($) {
  var I18nContent = function () {
    this.$toggleBtn = $('.togglebutton > label > input');
    this.$greedView = $('.grid-view');
    this.$multipleDeleteButton = $('.delete-multiple');
    this.init();
  };

  I18nContent.prototype = {
    constructor: I18nContent,

    init: function () {
      this.toggleStatus();
      this.onDeleteCheckedItems();
      this.iconPicker();
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


    onDeleteCheckedItems: function () {
      var me = this;
          me.$multipleDeleteButton.on('click', function (e) {
            e.preventDefault();
            var $itemIds, conf, deleteUrl;
               $itemIds = me.$greedView.yiiGridView('getSelectedRows');
                if($itemIds.length < 1){
                  return;
                }
               conf = confirm('Are you sure you want to delete this/these item(s)?');
                if(conf !== true) return;

                deleteUrl = $(this).data('url') || 'delete';
                me._post(deleteUrl, {id: $itemIds}).done(function (res) {
                  if(res.errorMsg){
                    alert(res.errorMsg);
                  }
                  //console.log(res);
                });
          });
    },

    iconPicker: function () {
      $('.icon-picker').iconpicker({}).on('iconpickerUpdated ', function (e) {
       $('.field-'+$(e.target).attr('id')).find('.om-icon-picker-preview .icon-preview i')[0].className = 'fa '+e.iconpickerInstance.iconpickerValue;
      })
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