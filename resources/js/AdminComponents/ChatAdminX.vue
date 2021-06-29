<template>
	<div>
		<div class="messaging col-md-9 offset-md-1 pt-5" @click="closeContextMenu">
			<div class="inbox_msg" >
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
						<div >
							<div class="chat_list" >	
								<div class="chat_people" :class="{active_chat:guest.active}" 
									v-for="(guest, index) in guestList" 
									@click="selectGuest(guest, index)" 
									@contextmenu="showContextMenu( 1, $event)"
									:id="'guest-' + index"
									
								>
							<!-- 	<button type="button" class="btn btn-danger"
		 @click.prevent="test($event)"
		 @contextmenu="showContextMenu( 1, $event)"
		 >
			button</button> -->
									<div class="chat_img"> 
										<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
									</div>
									<div class="chat_ib">
										<h5>{{guest.name}}<span class="chat_date">Dec 25</span></h5>
										<p>{{guest.chat[guest.chat.length -1 ].content}}.</p>
									</div>
								</div>
								
								
							</div>
						</div>
					</div>
				</div>
				<div class="mesgs"  v-for="(guest, index) in guestList" :key='index' v-if="guest.isShown" >
					<div  class="msg_history" style="height: 600px; overflow-y: scroll;">
						<div   v-for='(item) in guest.chat' >
							<div class="incoming_msg"  v-if="item.user ==='guest'" >
								<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> 
								</div>
								<div class="received_msg">
									<div class="received_withd_msg">
										<p>{{item.content}}</p>
										<span class="time_date"> 11:01 AM    |    June 9</span>
									</div>
								</div>
							</div>
							<div class="outgoing_msg"  v-if="item.user ==='admin'" >
								<div class="sent_msg">
									<p>{{item.content}}</p>
									<span class="time_date"> 11:01 AM    |    June 9</span> </div>
								</div>
							</div>
						</div>

						<small v-if="guest.isTyping"><i class="fas fa-pen-nib fa-fw fa-spin"></i>guest is typing...</small>
						<div class="type_msg">
							<div class="input_msg_write">
								<input type="text" class="write_msg" placeholder="Type a message" @keyup.enter="send" v-model="message" @input="type"/>
								<button class="msg_send_btn" type="button"  @keyup.enter="send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							</div>
						</div>
					</div>
			</div>
		</div>
<context-menu
	
	:is-context-menu="isContextMenu"
	:event-context-menu="eventContextMenu"
></context-menu>
		<!-- <test :event-context-menu='eventContextMenu' :is-content-menu="isContextMenu"></test> -->
	</div>

</template>

<script>
	import { mapState } from 'vuex'

	export default {
		props:{

		},
		data: function() {
			return {
				guestList:[
				{
					name: 'Adam',
					chat: [
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
					active: true,
					isTyping: false,
					
				},
				{
					name: 'Bob',
					chat: [
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
					active: false,
					isTyping: false,
				}
				],
				message:'',
				selectedGuest: '',
				selectedGuestIndex:'',
				msgReceived:true,
				isContextMenu: false,
				eventContextMenu: {},
				foo: 'foo'
			}
		},
		computed: {
			...mapState([

			])
		},
		methods: {
			test(e) {
				this.foo="bar";
				this.eventContextMenu = e;
			},
			showContextMenu(guestID, e){
					// console.log(guestID);
					this.isContextMenu = true;
					this.eventContextMenu = e;

			},
			closeContextMenu() {console.log('closeContextMenu')
					this.isContextMenu = false;
			},
			selectGuest(guest, index) {
				// this.isContextMenu = false;
				this.selectedGuest = guest;
				this.selectedGuestIndex = index;
				this.guestList.map( e => {
					e.isShown = false;
					e.active = false;

				});
				this.guestList[index].isShown = true;
				this.guestList[index].active = true;

				Echo.private(`admin-sent-message-${this.selectedGuest.name}`)
					.listenForWhisper('typing', (e) => {
						console.log(e.message);
						if(e.message.length > 0){
							this.guestList[this.selectedGuestIndex].isTyping = true;
						} else
						{
							this.guestList[this.selectedGuestIndex].isTyping = false
						}
					}).listenForWhisper('ChatEnd', (response) => {
						console.log(response);
						let checkGuest = containsGuest(this.guestList, response) ;
						let currentGuestIndex = checkGuest.index;
						document.getElementsByClassName('chat_people')[currentGuestIndex].classList.add("guest-leave-chat");
						alert('ChatEnd')
					});

			},
			send() {
				this.guestList[this.selectedGuestIndex].chat.push({
					user: 'admin',
					content: this.message
				});

				axios.post('/adminSentMessage', {
					guest: this.selectedGuest.name,
					message: this.message,
				})
				.then( (response) => {

				})
				.catch( (error) => {
					console.log(error);
				});

				this.message = '';

				//remove typing notification on guest side 
				this.type();
			},
			type() {
				Echo.private(`admin-sent-message-${this.selectedGuest.name}`)
				.whisper('typing', {
					name: 'admin',
					message: this.message
				});
			}
		},
		mounted() {

			Echo.private(`guest-sent-message`)
			.listen('GuestSentMessage', (result) => {
				console.log(result);
				let checkGuest = containsGuest(this.guestList, result) ;

				if( checkGuest.isOldGuest === true ) 
				{
					let currentGuestIndex = checkGuest.index;
					this.guestList[ currentGuestIndex ].chat.push({
						user: 'guest',
						content: result.message
					});
				} 
				else 
				{		
					let newGuest = {
						name: result.guest,
						chat: [{
							user: 'guest',
							content: result.message
						}],
						isShown: false,
						active: false,
						isTyping: false,
						
					}

					this.guestList.push(newGuest)
				}
			})




		},
		created() {
			let i = 0;
			setInterval( ( ) => {
				if( i % 2 === 0 && i < 5 ) {
					this.guestList[0].chat.push( {
						user: 'guest',
						content: randomStr(10)
					} ); 
					i++;
				} else if( i % 2 !== 0 && i < 5) {
					this.guestList[1].chat.push( {
						user: 'guest',
						content: randomStr(10)
					} );
					i++;
				}

			}, 10, i);

			axios.get('/api/getGuestList')
			.then( (response) => {
				//console.log(response.data.result);

				response.data.result.forEach( e => {
					this.guestList.push({
						id: e.id,
						name: e.name,
						chat: e.chat.messages,
						active: false,
						isShown: false,
						isTyping: false,

					});

				});
				//console.log(this.guestList)


			})
			.catch( (error) => {
				console.log(error);
			});


		},
		watch: {
			guestList: {
				deep: true,
				handler() {
					Vue.nextTick(function () {
						const div = document.getElementsByClassName('msg_history')[0];
						div.scrollTop = div.scrollHeight;
					});
				}
			},
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
			if(guestList[i].name === obj.guest) {
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

.guest-leave-chat{
	background-color: cyan;
}
</style>
