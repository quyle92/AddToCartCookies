<template>
	<div class="container">
		<div class="row">
			<div class="col-md-4 chat-widget">
				<div class="card">
					<div class="card-header" id="accordion"  @click.prevent="toggle">
						<div  v-bind:class="[isPreChat ? showIt : hideIt]"  class="pull-left">
							<i class="fas fa-lg fa-comment-dots"></i>  Fill in the form below to start chatting
						</div>
						<div v-bind:class="[isPreChat ? hideIt : showIt]" class="pull-left">
							<i class="fas fa-lg fa-comment-dots"></i> Chat
						</div>
						<div class="btn-group pull-right">
							<i class="fas  fa-lg" 
							:class="[showChatToggle ? 'fa-arrow-alt-circle-down' : 'fa-arrow-alt-circle-up' ]">
						</i>
					</div>
				</div>

				<div class="card-body" v-bind:class="[isPreChat ? showIt : hideIt]" v-if="showChatToggle">
					<div class="form-group">
						<label for="guest_name">Name</label>
						<input type="text" class="form-control" id="guest_name"  placeholder="Enter your name" v-model="guestName" @keyup.enter="submit"/>
					</div>
					<b-button variant="primary" :disabled="isSubmit" @click.prevent="submit">
				    <b-spinner small v-if="isSubmit"></b-spinner>
				    Submit
				  </b-button>
				</div>
			</div>
			<div class="card-collapse collapse show" id="collapseOne" v-bind:class="[isPreChat ? hideIt : showIt]" v-if="showChatToggle">

				<div class="card-body" id="chatMsg">
					<ul class="chat incoming_msg">
						<li class=" clearfix" v-for="item in messages" >
							<div :class="[item.user==='admin' ? 'left' : '']" v-if="item.user==='admin'">
								<span class="chat-img pull-left" >
									<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
								</span>
								<div class="chat-body clearfix" >
									<div class="header">
										<strong class="primary-font">{{item.user}}</strong> 
										<small class="pull-right text-muted">
											<span class="glyphicon glyphicon-time"></span>12 mins ago
										</small>
									</div>
									<p>
										{{item.msg}}
									</p>
								</div>
							</div>

							<div :class="[item.user!=='admin' ? 'right' : '']" v-if="item.user!=='admin'">
								<span class="chat-img pull-right" >
									<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
								</span>
								<div class="chat-body clearfix" >
									<div class="header">
										<small class=" text-muted">
											<span class="glyphicon glyphicon-time"></span>13 mins ago
										</small>
										<strong class="pull-right primary-font" id="guestName">{{item.user}}</strong>
									</div>
									<p>
										{{item.msg}}
									</p>
								</div >
							</div>

						</li>
						<div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="isChatEnd">
						  <strong>Chat Ended!</strong> Thanks for your conversation.
						  <button type="button" class="close"  aria-label="Close" 
						  @click.prevent="closeChatEnd">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>
						
					</ul>

				</div>
				
				<small v-if="isTyping"><i class="fas fa-pen-nib fa-fw fa-spin"></i>admin is typing...</small>
				<div class="card-footer">
					<div class="input-group" >
						<input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." @keyup.enter="send" v-model="message" @input="type" v-focus :disabled="isError || isChatEnd" />
						<span class="input-group-btn">
							<button class="btn btn-warning btn-sm" id="btn-chat" @click.prevent="send">
							Send</button>
							<button class="btn btn-info btn-sm" id="btn-chat" @click.prevent="closeChatEnd">
							End</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import { mapState } from 'vuex'
import { mapGetters } from 'vuex'
import { BButton, BSpinner   } from 'bootstrap-vue'
Vue.component('b-button', BButton)
Vue.component('b-spinner', BSpinner)


export default {
	props:{

	},
	data: function() {
		return {
			isSubmit: false,
			showIt: 'showIt',
			hideIt: 'hideIt',
			message:'',
			isTyping: false,
			timer: null,
			showChatToggle: true,
			disabled: false,
			guestName: '',
			isError: false,
			// alreadyEnd: false
		}
	},
	computed: mapState(
		['messages', 'isPreChat', 'isChatEnd', 'guest', 'guestId']
	),
	methods: {
		submit(){
			this.isSubmit = true;
			axios.post('/joinChat', {
			    guest: this.guestName,
				})
				.then( (response) => {
					this.isSubmit = false;
					this.$store.commit('SET_GUEST_ID', response.data.guest.id);
					this.$store.commit('TOGGLE_IS_CHAT_END', false);
					this.$store.commit('TOGGLE_IS_PRECHAT', false);
					this.registerGuest();
				})
				.catch( (error) => {
				    console.log(error);
				});

  		},

  	registerGuest() {
  		if(this.guest.length === 0) {
  			this.$store.commit('SET_GUEST', this.guestName);
  		}
	   // get incoming messages
  		Echo.private(`admin-sent-message-${this.guestId}`)
	  		.listen('AdminSentMessage', (result) => {
	  			console.log(result);	
	  			let data = {
	  				user: 'admin',
	  				msg: result.message
	  			}
	  			this.$store.commit('ADD_MESSAGES', data);
	  			Vue.nextTick(function () {
	  				vueChatScroll();
	  			});

	  		})
	  		.listenForWhisper('typing', (e) => {
	  			console.log(e)
	  			if(e.message.length > 0){
	  				this.isTyping = true;
	  			} else
	  			{
	  				this.isTyping = false
	  			}
		  	}) 	
		  	.listenForWhisper('ChatEndFromAdmin', (response) => {
		  		this.$store.commit('TOGGLE_IS_CHAT_END', true);

			});
	  		
	  		
	},
  	type() {
  		Echo.private(`admin-sent-message-${this.guestId}`)
  		.whisper('typing', {
  			name: 'guest',
  			message: this.message
  		});
  	},
  	toggle() {
  		this.showChatToggle = ! this.showChatToggle;
  	},
  	send() {
  		
  		if( this.message.length === 0 || this.isChatEnd === true ) return;

  		let payload = {
  			user: 'guest',
  			msg: this.message,
  			time: new Date()
  		}
  		this.$store.commit('ADD_MESSAGES', payload);

  		Vue.nextTick( function () {
  			vueChatScroll();
  		});

  		axios.post('/guestSentMessage', {
  			guest: this.guest,
  			message: this.message,
  			
  		})
  		.then( (response) => {
					//remove typing notification on admin side 
					this.type();
  		})
  		.catch( (error) => {
  			console.log(error);
  			this.isError = true;
  			alert('Sth Wrong happen. You cannot send chat messages now!')
  		});

  		this.message = '';
  		
	},
	closeChatEnd() {
		
		// if(this.alreadyEnd === true) {
			this.endChatAtClient();
			this.deleteGuestIdAtServer();
		// }

		this.showChatToggle = true;
		this.$store.commit('ADD_MESSAGES', '');
		this.isTyping = false;



		this.$store.commit('REMOVE_MESSAGES');
		this.$store.commit('TOGGLE_IS_CHAT_END', true);
		this.$store.commit('TOGGLE_IS_PRECHAT', true);
		this.$store.commit('SET_GUEST', '');
		this.$store.commit('SET_GUEST_ID', '');
		
		


		
	},
	endChatAtClient() {
		// Echo.private(`admin-sent-message-${this.guestId}`).stopListening('AdminSentMessage')
			Echo.private(`admin-sent-message-${this.guestId}`).whisper('ChatEnd',{ guest: this.guest, 
				id: this.guestId });
			Echo.leave(`admin-sent-message-${this.guestId}`)//(1)
	},
	deleteGuestIdAtServer(){
		axios.patch(`/api/chatEnd/${this.guestId}`);
	}
}, 
mounted() {
	if( this.guest.length > 0 ) {
		this.registerGuest();
	}

	//if the messages in localStorage is > 30 mins, it will be deleled
	let time = this.messages[this.messages.length - 1].time;
	if (this.messages.length > 1 && new Date(Date.parse(time)).addMinutes(30) < new Date()) {
		this.closeChatEnd();

	}


},
updated() {
	Vue.nextTick(function () {

		vueChatScroll();
	});
},
created() {

},
directives: {
  focus: {
    // directive definition
    update: function (el) {
      el.focus()
    }
  }
},
watch: {
	IncomingMessages() {
		vueChatScroll();
	},
	messages: {
		deep: true,
		handler() {

			if (this.timer) {
			    clearTimeout(this.timer); //cancel the previous timer.
			    this.timer = null;
			}

			// automatically end chat after 15s
			this.timer = setTimeout(() => {
				if(this.messages.length > 1) {
					this.$store.commit('TOGGLE_IS_CHAT_END', true);
					this.disabled = true;
					this.endChatAtClient();
					this.deleteGuestIdAtServer();
					// this.alreadyEnd === true;
				}	
			}, 130000000);
		}
	},
	guestId(val) {
	
	}


}


//end
}

function vueChatScroll() {
	const div = document.getElementById('chatMsg') ?? null;
	if(div !== null)
		div.scrollTop = div.scrollHeight;
}

</script>
<!--Note
//(1): leave chat phải để sau cùng

	-->
<style scoped>
@import "../../sass/chat-guest.css";
</style>
