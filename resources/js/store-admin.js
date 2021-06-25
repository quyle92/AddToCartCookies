import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import Helper from './helper';
let _helper = new Helper()

export default new Vuex.Store({
  state: {
    guestList:[
        {
          name: 'Adam',
          msg: [
            {
              user: 'guest',
              content: 'hi there,'
            },
            {
              user: 'admin',
              content: 'Hey bro!'
            },
            {
              user: 'guest',
              content: 'how are you?'
            },
            {
              user: 'admin',
              content: 'Great! I am glad to see you too!'
            }
          ],
          isShown: true,
          active: true
        },
        {
          name: 'Bob',
          msg: [
            {
              user: 'guest',
              content: 'I am Bob'
            },
            {
              user: 'admin',
              content: 'hi there'
            },
            {
              user: 'guest',
              content: 'Nice to meet you!'
            },
            {
              user: 'admin',
              content: 'how are you?'
            }
          ],
          isShown: false,
          active: false
        }
      ],
  },
  mutations: {
  },
  actions: {
  },
  plugins: [

  ],

})
