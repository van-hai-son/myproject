import Vue from 'vue'
import OrderComponent from './components/order.vue'
import ProductComponent from './components/product.vue'

Vue.component('order-component', OrderComponent)
Vue.component('product-component', ProductComponent)
new Vue({ el: '#app' })

