<template>
	<div>
		<div class="messaging col-md-9 offset-md-1">
		  <div class="inbox_msg">
				<div class="inbox_people">
				  	<div class="headind_srch">
								<div class="recent_heading">
								  <h4>Recent</h4>
								</div>
								<div class="srch_bar">
								  	<div class="stylish-input-group">
										<input type="text" class="search-bar"  placeholder="Search" >
									</div>
								</div>
				  	</div>
						<div class="inbox_chat">
							<div style="max-height: 600px; overflow-y: scroll;">
									<div class="chat_list " >
									  	<div class="chat_people" :class="{active_chat:guest.active}" v-for="(guest, index) in guestList" @click="selectGuest(index)">
												<div class="chat_img"> 
													<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
												</div>
												<div class="chat_ib">
												  <h5>{{guest.name}}<span class="chat_date">Dec 25</span></h5>
												  <p>{{guest.msg[guest.msg.length -1 ]}}.</p>
												</div>
									  	</div>
									</div>
							</div>
				  	</div>
				</div>
				<div class="mesgs"   v-for="(guest, index) in guestList" :key='index' v-if="guest.isShown">
					<div style="max-height: 600px; overflow-y: scroll;" v-chat-scroll>
						  <div class="msg_history"  v-for='(item, indexMsg) in guest.msg' :key='indexMsg'  >
									<div class="incoming_msg">
									  <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> 
									  </div>
									  <div class="received_msg">
											<div class="received_withd_msg">
											  <p>{{item}}</p>
											  <span class="time_date"> 11:01 AM    |    June 9</span>
											</div>
									  </div>
									</div>
									<!-- <div class="outgoing_msg">
									  <div class="sent_msg">
										<p>Test which is a new approach to have all
										  solutions</p>
										<span class="time_date"> 11:01 AM    |    June 9</span> </div>
									</div> -->
						  </div>
					</div>
				  <div class="type_msg">
							<div class="input_msg_write">
							  <input type="text" class="write_msg" placeholder="Type a message" />
							  <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
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
		 		guestList:[
			  	{
			  		name: 'Adam',
			  		msg: ['I am Adam', 'How\'re you?', 'Smile...'],
			  		isShown: true,
			  		active: true
			  	},
			  	{
			  		name: 'Bob',
			  		msg: ['Bob here!!!', 'Are you there?', 'Phew...'],
			  		isShown: false,
			  		active: false
			  	}
		  	],
 	  }
  },
  computed: {

  },
  methods: {
  		selectGuest(index) {
  		
  			this.guestList.map( e => {
  				 e.isShown = false;
  				 e.active = false;

  			});
  			this.guestList[index].isShown = true;
  			this.guestList[index].active = true;

  		}
  },
  mounted() {
  	Echo.channel('chat-room')
    .listen('ChatEvent', (result) => {
        console.log(result);
      	let checkGuest = containsGuest(this.guestList, result) ;

      	if( checkGuest.isOldGuest === true ) 
      	{
      		let currentGuestIndex = checkGuest.index;
      		this.guestList[ currentGuestIndex ].msg.push(result.message);
      	} 
      	else 
      	{		
      			let newGuest = {
      				name: result.user,
				  		msg: [result.message],
				  		isShown: false,
				  		active: false
      			}

      			this.guestList.push(newGuest)
      	}
    });

  },
  created() {
	  	let i = 0;
	  // 	setInterval( ( ) => {
	  // 		if( i % 2 === 0  ) {
	  // 			this.guestList[0].msg.push( randomStr(10) ); 
	  // 			i++;
	  // 		} else if( i % 2 !== 0) {
	  // 			this.guestList[1].msg.push( randomStr(10) );
	  // 			i++;
	  // 		}
	  // 	}, 10, i);
  	

   }
}

function randomStr(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
   return result;
}

function containsGuest(guestList, obj) 
{
	for( let i = 0; i < guestList.length; i ++ ) {
			if(guestList[i].name === obj.user) {
				return {
					isOldGuest: true,
					index: i
				}
				
			}
	}

	return {
					isOldGuest: false,
				}

} 
</script>

<style scoped>

/*---------chat window---------------*/
.inbox_people {
	background: #fff;
	/*float: left;*/
	overflow: hidden;
	width: 30%;
	border-right: 1px solid #ddd;
	display: table-cell;
}

.inbox_msg {
	border: 1px solid #ddd;
	clear: both;
	overflow: hidden;
	display: table-row;
}

.top_spac {
	margin: 20px 0 0;
}

.recent_heading {
	float: left;
	width: 40%;
}

.srch_bar {
	display: inline-block;
	text-align: right;
	width: 60%;
	padding:
}

.headind_srch {
	padding: 10px 29px 10px 20px;
	overflow: hidden;
	border-bottom: 1px solid #c4c4c4;
}

.recent_heading h4 {
	color: #0465ac;
    font-size: 16px;
    margin: auto;
    line-height: 29px;
}

.srch_bar input {
	outline: none;
	border: 1px solid #cdcdcd;
	border-width: 0 0 1px 0;
	width: 80%;
	padding: 2px 0 4px 6px;
	background: none;
}

.srch_bar .input-group-addon button {
	background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
	border: medium none;
	padding: 0;
	color: #707070;
	font-size: 18px;
}

.srch_bar .input-group-addon {
	margin: 0 0 0 -27px;
}

.chat_ib h5 {
	font-size: 15px;
	color: #464646;
	margin: 0 0 8px 0;
}

.chat_ib h5 span {
	font-size: 13px;
	float: right;
}

.chat_ib p {
    font-size: 12px;
    color: #989898;
    margin: auto;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat_img {
	float: left;
	width: 11%;
}

.chat_img img {
	width: 100%
}

.chat_ib {
	float: left;
	padding: 0 0 0 15px;
	width: 88%;
}

.chat_people {
	border-bottom: 1px solid #ddd;
	margin: 0;
	padding: 18px 16px 10px;
	overflow: hidden;
	clear: both;
}

.chat_list {
	/*border-bottom: 1px solid #ddd;*/
	/*margin: 0;
	padding: 18px 16px 10px;*/
	cursor: pointer;
}

.inbox_chat {
	/*height: 550px;
	overflow-y: scroll;*/
}

.active_chat {
	background: #e8f6ff;
}

.incoming_msg_img {
	display: inline-block;
	width: 6%;
}

.incoming_msg_img img {
	width: 100%;
}

.received_msg {
	display: inline-block;
	padding: 0 0 0 10px;
	vertical-align: top;
	width: 92%;
}

.received_withd_msg p {
	background: #ebebeb none repeat scroll 0 0;
	border-radius: 0 15px 15px 15px;
	color: #646464;
	font-size: 14px;
	margin: 0;
	padding: 5px 10px 5px 12px;
	width: 100%;
}

.time_date {
	color: #747474;
	display: block;
	font-size: 12px;
	margin: 8px 0 0;
}

.received_withd_msg {
	width: 57%;
}

.mesgs{
	/*float: left;*/
	padding: 30px 15px 0 25px;
	width:70%;
	display: table-cell;
	/*max-height: 600px;
	overflow-y: scroll;*/
}

.sent_msg p {
	background:#0465ac;
	border-radius: 12px 15px 15px 0;
	font-size: 14px;
	margin: 0;
	color: #fff;
	padding: 5px 10px 5px 12px;
	width: 100%;
}

.outgoing_msg {
	overflow: hidden;
	margin: 26px 0 26px;
}

.sent_msg {
	float: right;
	width: 46%;
}

.input_msg_write input {
	background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
	border: medium none;
	color: #4c4c4c;
	font-size: 15px;
	min-height: 48px;
	width: 100%;
	outline:none;
}

.type_msg {
	border-top: 1px solid #c4c4c4;
	position: relative;
}

.msg_send_btn {
	background: #05728f none repeat scroll 0 0;
	border:none;
	border-radius: 50%;
	color: #fff;
	cursor: pointer;
	font-size: 15px;
	height: 33px;
	position: absolute;
	right: 0;
	top: 11px;
	width: 33px;
}

.messaging {
	padding: 0 0 50px 0;
	display: table;
}

/*.msg_history {
	height: inherit;
	overflow-y: scroll;

}*/
</style>
