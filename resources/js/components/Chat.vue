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
                            	:class="[showChat ? 'fa-arrow-alt-circle-down' : 'fa-arrow-alt-circle-up' ]">
                            </i>
		                </div>
	                </div>

					<div class="card-body" v-bind:class="[isPreChat ? showIt : hideIt]" v-if="showChat">
					    <div class="form-group">
						    <label for="guest_name">Name</label>
						    <input type="text" class="form-control" id="guest_name"  placeholder="Enter your name" v-model="guest" @keyup.enter="submit"/>
						</div>
						<button type="submit" class="btn btn-primary submit" @click.prevent="submit">Submit</button>
					</div>
				</div>
	            <div class="card-collapse collapse show" id="collapseOne" v-bind:class="[isPreChat ? hideIt : showIt]" v-if="showChat">

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
	                    </ul>
	                </div>
	                <small v-if="isTyping"><i class="fas fa-pen-nib fa-fw fa-spin"></i>admin is typing...</small>
	                <div class="card-footer">
	                    <div class="input-group">
	                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." @keyup.enter="send" v-model="message" @input="type"/>
	                        <span class="input-group-btn">
	                            <button class="btn btn-warning btn-sm" id="btn-chat" @click.prevent="send">
	                                Send</button>
	                        </span>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>

export default {
  props:{

  },
  data: function() {
    return {
 		isPreChat: true,
 		showIt: 'showIt',
 		hideIt: 'hideIt',
 		showChat: true,
 		guest:'',
 		message:'',
 		messages:[
 			{
 				user: 'admin',
 				msg:'Hi there, may I help you?'
 			},
 			{
 				user: 'guest',
 				msg:'Yes, I need help'
 			},
 		],
 		isTyping: false

 	  }
  },
  computed: {
    getGuest(guest) {
            return guest
        }
    },
  methods: {
  	submit(){
  		this.isPreChat = false;

  		//get incoming messages
		Echo.private(`AdminSentMessageTo_${this.guest}`)
	    	.listen('AdminSentMessage', (result) => {
    			console.log(result);
    			this.messages.push({
    				user: 'admin',
 					msg: result.message
    			});

    			Vue.nextTick(function () {
				    vueChatScroll();
				});
		    	
	    });

	    Echo.private(`GuestSentMessage`)
	    	.listenForWhisper('typing', (e) => {
	       		console.log(e.message);
	       		if(e.message.length > 0){
	       			this.isTyping = true;
	       		} else
	       		{
	       			this.isTyping = false
	       		}
	    });
  		
  	},
  	type() {
  		Echo.private(`AdminSentMessageTo_${this.guest}`)
		    .whisper('typing', {
		        name: 'guest',
		        message: this.message
		});
  	},
  	toggle() {
  		this.showChat = ! this.showChat;
  	},
  	send() {
  		this.messages.push({
  			user: 'guest',
 			msg: this.message
  		});

  		Vue.nextTick(function () {
		    vueChatScroll();
		});
  		


  		axios.post('/guestSentMessage', {
		    guest: this.guest,
		    message: this.message,
		  })
		  .then( (response) => {
		    
		  })
		  .catch( (error) => {
		    console.log(error);
		});

		this.message = '';

		//remove typing notification on admin side 
		this.type();
  	}
  }, 
  mounted() {
	
  },
   updated() {
  		 Vue.nextTick(function () {
	    	   	
		    vueChatScroll();
		});
   },
  created() {
  
  },
  watch: {
  	IncomingMessages() {
  		vueChatScroll();
  	},
  	guest(val) {
  		
  	}
  }
}

function vueChatScroll() {
	const div = document.getElementById('chatMsg');
	div.scrollTop = div.scrollHeight;
}

</script>

<style scoped>
.chat-widget {
	position: fixed;
	bottom: 1px;
	left: 1px;
	z-index: 9999;
}
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li .left .chat-body
{
    margin-left: 60px;
}

.chat li .right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.card .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.card-body
{
    overflow-y: scroll;
    height: 250px;
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

.btn.submit:hover, .card-header:hover{
	background: #CDCDCD;
}

.card-header{
	cursor: pointer;
}

.showIt {
	display: block;
}

.hideIt {
	display: none;
}

#collapseOne {
	background-color: #fff;
}
</style>
