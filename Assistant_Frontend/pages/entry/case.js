// pages/entry/case.js
Page({
  data: {
    isOwner: false
  },
  
  onLoad: function (options) {
    var id = options.id;
    this.setData({
      case_id: id
    });

    wx.request({
      url: 'https://pww.wanqingbo.com/api/content?id='+id,
      success: res => {
        var content = res.data[0]
        this.setData({
          avatar: content.avatar,
          nickname: content.nickname,
          create_date: content.create_date,
          context: content.context,
          question: content.question,
        })
      }
    });

    wx.request({
      url: 'https://pww.wanqingbo.com/api/is_owner/',
      method: 'post',
      data: {
        token: wx.getStorageSync('token'),
        case_id: id
      },
      success: res => {
        if(res.data == 1) {
          this.setData({
            isOwner: true
          })
        }
      }
    })
  },

  // 提交拆解答案
  submit_answer: function(e) {
    wx.navigateTo({
      url: 'reply?id=' + this.data.case_id,
    })
  },

  // 查看拆解列表
  view_answers: function(e) {
    wx.navigateTo({
      url: 'reply_list?id=' + this.data.case_id,
    })
  },

  // 设置分享页面
  onShareAppMessage: function() {
    return {
      title: '第'+this.data.case_id+'期私董会案例',
      path: '/pages/entry/case?id='+this.data.case_id
    }
  }
})