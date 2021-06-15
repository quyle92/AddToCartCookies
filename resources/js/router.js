import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import Content from './AdminComponents/Content'
import Dashboard from './AdminComponents/Dashboard'
import Users from './AdminComponents/Users'

const routes = [
	{
		path: '/admin',
		component: Content,
		children: [
        {
          // UserProfile will be rendered inside User's <router-view>
          // when /user/:id/profile is matched
          path: 'dashboard',
          component: Dashboard
        },
        {
          // UserPosts will be rendered inside User's <router-view>
          // when /user/:id/posts is matched
          path: 'users',
          component: Users
        }
      ]
	}
];

export default new Router({
	mode: 'history',
	routes,
	linkActiveClass:  "active"
});