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
		                        <a type="button" class="btn btn-default btn-xs" data-parent="#accordion" href="#collapseOne">
		                            <i class="fas  fa-lg" 
		                            	:class="[showChat ? 'fa-arrow-alt-circle-down' : 'fa-arrow-alt-circle-up' ]">
		                            </i>
		                        </a>
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
	                        <li class="left clearfix">
	                        	<span class="chat-img pull-left">
	                            	<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
	                        	</span>
	                            <div class="chat-body clearfix">
	                                <div class="header">
	                                    <strong class="primary-font">Admin</strong> <small class="pull-right text-muted">
	                                        <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
	                                </div>
	                                <p>
	                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
	                                    dolor, quis ullamcorper ligula sodales.
	                                </p>
	                            </div>
	                        </li>
	                    </ul>
	                    <ul class="chat outgoing_msg" >
	                        <li class="right clearfix" v-for="msg in OutgoingMessages" >
	                        	<span class="chat-img pull-right">
	                            	<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
	                        	</span>
	                            <div class="chat-body clearfix">
	                                <div class="header">
	                                    <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
	                                    <strong class="pull-right primary-font">You</strong>
	                                </div>
	                                <p>
	                                    {{msg}}
	                              	</p>
	                            </div >
	                        </li>
	                    </ul>
	                </div>
	                <div class="card-footer">
	                    <div class="input-group">
	                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." @keyup.enter="send" v-model="message"/>
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
 		OutgoingMessages: []
 	  }
  },
  methods: {
  	submit(){
  		this.isPreChat = false;
  		
  	},
  	toggle() {
  		this.showChat = ! this.showChat;
  	},
  	send() {
  		this.OutgoingMessages.push(this.message);

  		Vue.nextTick(function () {
		    const div = document.getElementById('chatMsg');
		    div.scrollTop = div.scrollHeight;
		    console.log(div.scrollTop, div.scrollHeight)
		});

  		axios.post('/sendMessage', {
		    guest: this.guest,
		    message: this.message,
		  })
		  .then( (response) => {
		  
		    this.message = '';
		  })
		  .catch( (error) => {
		    console.log(error);
		});
		  
  	}
  }, 
  mounted() {
  		const messages = document.getElementById('chatMsg');
	  	let shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
	  	if (!shouldScroll) {
		    scrollToBottom( messages );
		}
  },
  created() {
  
  },
  watch: {

  }
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

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
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
