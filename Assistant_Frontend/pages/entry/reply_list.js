// pages/entry/reply_list.js
Page({
  // 页面的初始数据
  data: {
    vote_num: 0,
    isOwner: false,
    is_best: false,
    case_id: null,
    option_array: ['全部拆解', '优秀拆解']
  },

  // 页面加载拆解列表
  onLoad: function (options) {
    var id = this.data.case_id = options.id;
    wx.request({
      url: 'https://pww.wanqingbo.com/api/reply_list?id='+id,
      success: res => {
        this.setData({
          reply_list: res.data
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
        if (res.data == 1) {
          this.setData({
            isOwner: true
          })
        }
      }
    })
  },

  set_best: function(e) {
    wx.request({
      url: 'https://pww.wanqingbo.com/api/set_best/',
      method: 'post',
      data: {
        reply_id: e.currentTarget.dataset.rid,
        token: wx.getStorageSync('token'),
        case_id: this.data.case_id
      },
      success: res => {
        this.setData({
          reply_list: res.data
        })
      }
    })
  },

  vote_reply: function(e) {
    wx.request({
      url: 'https://pww.wanqingbo.com/api/vote_reply/',
      method: 'post',
      data: {
        reply_id: e.currentTarget.dataset.rid,
        token: wx.getStorageSync('token'),
        case_id: this.data.case_id
      },
      success: res => {
        this.setData({
          reply_list: res.data
        })
      }
    })
  },

  // 筛选拆解
  bindPickerChange: function(e) {
    this.setData({
      index: e.detail.value
    })
    wx.request({
      url: 'https://pww.wanqingbo.com/api/filter_reply/',
      method: 'post',
      data: {
        case_id: this.data.case_id,
        index: e.detail.value
      },
      success: res => {
        this.setData({
          reply_list: res.data
        })
      }
    })
  }
})