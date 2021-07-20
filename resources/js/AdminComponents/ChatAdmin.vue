<template>
	<div>
		<div class="messaging col-md-9 offset-md-1 pt-5" @click="closeContextMenu">
			<div class="inbox_msg" >
				<div class="inbox_people">
					<div class="headind_srch">
						<div class="recent_heading">
							<button class="btn btn-danger btn-sm" @click.prevent="deleteChatAll()"><i class="fas fa-trash"></i></button>
						</div>
						<div class="srch_bar">
							<div class="stylish-input-group">
								<input type="text" class="search-bar"  placeholder="Search" >
							</div>
						</div>
					</div>
					<div class="inbox_chat">
							<div class="chat_list" style="height: 400px; overflow-y: scroll;">
								<div class="chat_people"
									:class="[{active_chat:guest.active}, {'guest-leave-chat': guest.isChatEnd}]"
									v-for="(guest, index) in guestList"
									@click="selectGuest(guest, index, $event)"
									@contextmenu.prevent="showContextMenu( index, guest.id, $event)"
									:id="'guest-' + index"
								>
									<div class="chat_img">
										<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
									</div>
									<div class="chat_ib" ref="chat_ib">
										<h5 :style="[! guest.isRead ? hightlightText: {}]">{{guest.name}}<span class="chat_date">Dec 25</span></h5>
										<p :style="[! guest.isRead ? hightlightText: {}]">{{guest.chat.length > 0 ? guest.chat[guest.chat.length -1 ].content : ''}}.</p>
									</div>
								</div>
							</div>
					</div>
				</div>
				<div class="mesgs"  >
						<div  class="msg_history" style="height: 400px; overflow-y: scroll;">
							<div class="incoming_msg_img" v-if="isObjEmpty(selectedGuest) || selectedGuest.chat.length === 0"></div>
							<div   v-for='(item) in selectedGuest.chat' >

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

						<small v-if="selectedGuest.isTyping"><i class="fas fa-pen-nib fa-fw fa-spin"></i>guest is typing...</small>
						<div class="type_msg" v-if="selectedGuest">
							<div class="input_msg_write">
								<input type="text" class="write_msg" placeholder="Type a message" @keyup.enter="send" v-model="message" @input="type" :disabled="isError"/>
								<button class="msg_send_btn" type="button"  @keyup.enter="send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							</div>
						</div>
					</div>
			</div>
		</div>
<context-menu
	:is-context-menu="isContextMenu"
	:event-context-menu="eventContextMenu"
	:guest-index="guestIndex"
	:guest-id="guestId"
	@delete="deleteChat"
></context-menu>

	</div>

</template>

<script>
	import { mapState } from 'vuex'
// import Helper from '../helper';
// let $Helper = new Helper;
// console.log(Helper)
	export default {
		props:{

		},
		data: function() {
			return {
				guestList:[],
				message:'',
				msgReceived:true,
				isContextMenu: false,
				eventContextMenu: {},
				foo: 'foo',
				guestIndex: '',
				guestId: '',
				isError: false,
				hightlightText: {'font-weight': 'bold!important', 'font-size':'16px!important'},
				deletedChatId:''
			}
		},
		computed: {
			...mapState([
					'selectedGuest', 'selectedGuestIndex',
			])
		},
		methods: {
			showContextMenu(index, guestId, e){
				// console.log(guestId);debugger
				this.isContextMenu = true;
				this.eventContextMenu = e;
				this.guestIndex = index
				this.guestId = guestId

				Echo.private(`admin-sent-message-${this.guestId}`)
          			.listen('AdminSentMessage', (e) => {
		              console.log()
		          	});

			},
			closeContextMenu() {
					this.isContextMenu = false;

			},
			deleteChat(data){
				Echo.private(`admin-sent-message-${this.guestId}`)
					.whisper('ChatEndFromAdmin',{id: this.guestId});

				axios.delete('/api/deleteChat', data)
                	.then( (response) => {
                		console.log(response.data);
                		this.$store.commit('SET_SELECTED_GUEST', '');
						this.$store.commit('SET_SELECTED_GUEST_INDEX', '');
						this.guestList.splice(this.guestIndex, 1);

            		}).catch( (error) => {
                		console.log(error);
            		});


			},
			deleteChatAll(){
				for (var i = 0; i < this.guestList.length; i++) {
					let id = this.guestList[i].id;
					Echo.private(`admin-sent-message-${id}`)
					.whisper('ChatEndFromAdmin',{id: id});
				}

				axios.delete('/api/deleteChatAll')
                	.then( (response) => {
                		this.$store.commit('SET_SELECTED_GUEST', '');
						this.$store.commit('SET_SELECTED_GUEST_INDEX', '');

						this.guestList = [];

            		}).catch( (error) => {
                		alert(error.response.data.msg)
            		});



			},
			selectGuest(guest, index, e) {console.log('selectedGuest')
				guest.isRead = true;
				this.$store.commit('SET_SELECTED_GUEST', guest);
				this.$store.commit('SET_SELECTED_GUEST_INDEX', index);
				this.guestList.map( e => {

					e.active = false;

				});
				this.guestList[index].active = true;

				Echo.private(`admin-sent-message-${this.selectedGuest.id}`)
					.listenForWhisper('typing', (e) => {
						console.log('typing',e.message);
						if(e.message.length > 0){
							this.selectedGuest.isTyping = true;
						} else
						{
							this.selectedGuest.isTyping = false
						}
					});


				axios.patch(`/api/markAsRead/${this.selectedGuest.id}`);


			},
			send() {
				if( this.message === '' ) return;

				axios.post('/adminSentMessage', {
					guest_id: this.selectedGuest.id,
					guest: this.selectedGuest.name,
					message: this.message,
				})
				.then( (response) => {
						this.guestList[this.selectedGuestIndex].chat.push({
							user: 'admin',
							content: this.message
						});

						this.$store.commit('SET_SELECTED_GUEST', this.guestList[this.selectedGuestIndex]);
						//remove typing notification on guest side
						this.type();

				})
				.catch( (error) => {
					console.log(error);
					this.isError = true
					alert('Errors. Please fix it!')
				});

				this.message = '';

			},
			isObjEmpty(obj){
				return this.$Helper.isObjEmpty(obj);
			},
			type() {
				Echo.private(`admin-sent-message-${this.selectedGuest.id}`)
				.whisper('typing', {
					name: 'admin',
					message: this.message
				});
			}
		},
		mounted() {

			Echo.private(`guest-sent-message`)
			.listen('GuestSentMessage', (result) => {

				let checkGuest = containsGuest(this.guestList, result) ;

				if( checkGuest.isOldGuest === true )
				{
					let currentGuestIndex = checkGuest.index;
					this.guestList[ currentGuestIndex ].chat.push({
						user: 'guest',
						content: result.message
					});
					this.guestList[ currentGuestIndex ].isTyping = false;

					//Edge case
					if(this.guestList[currentGuestIndex].id === this.selectedGuest.id)
						this.$store.commit('SET_SELECTED_GUEST', this.guestList[currentGuestIndex]);

					Push.create(result.guest, {
		                body: result.message,
		                icon: '/images/laravel.png',
		                timeout: 4000,
		                onClick:  () => {
		                    window.focus();
		                    this.selectGuest(this.guestList[currentGuestIndex], currentGuestIndex);
		                    //this.close();
		                }
		            });
				}
				else
				{
					let newGuest = {
						id: result.id,
						name: result.guest,
						chat: [{
							user: 'guest',
							content: result.message
						}],
						active: false,
						isTyping: false,

					}

					this.guestList.push(newGuest);

					Push.create(result.guest, {
		                body: result.message,
		                icon: '/images/laravel.png',
		                timeout: 4000,
		                onClick:  () => {
		                    window.focus();
		                    this.selectGuest(this.guestList[this.guestList.length - 1], this.guestList.length - 1);
		                    //this.close();
		                }
		            });

				}




			});

			let adminId = $('meta[name="admin-id').attr('content');
			Echo.private('App.User.' +  adminId).notification((notification) => {
	        	console.log('notification: ', notification);
	    	});



			vm.$on('closeContextMenu', () => {
				this.isContextMenu = false
			});

		},
		created() {

			axios.get('/api/getGuestList')
				.then( (response) => {
					console.log(response.data)
				response.data.result.forEach( e => {
					if(e.chat !== null)
						this.guestList.push({
								id: e.id,
								name: e.name,
								chat: e.chat.messages ?? [],
								active: false,
								isTyping: false,
								isChatEnd: e.chat.is_chat_end,
								isRead: e.chat.is_read
						});

				});

			//Edge case
			if( this.guestList.length > 0)
			{
				//check if the guest is not in chat list or he has been out
				let selectedGuest = this.$Helper.isObjEmpty(this.selectedGuest) || this.guestList.filter( o => o.id === this.selectedGuest.id ).length === 0 ?  this.guestList[0] : this.selectedGuest ;

				if( selectedGuest.id === this.guestList[0].id )
				{
					this.$store.commit('SET_SELECTED_GUEST_INDEX', 0)
				}

				let selectedGuestIndex = this.selectedGuestIndex || 0;
				this.selectGuest(selectedGuest, selectedGuestIndex);

			} else {
				this.$store.commit('SET_SELECTED_GUEST', '')
			}


			})
			.catch( (error) => {
				console.log(error);
			});

		document.addEventListener('click', () => {

		})



		},
		watch: {
			guestList: {
				deep: true,
				handler(newVal, oldVal) {
					Vue.nextTick( () => {
						var div = document.getElementsByClassName('msg_history')[0];//debugger
						div.scrollTop = div.scrollHeight;
					});

					for (var i = 0; i < this.guestList.length; i++) {

						Echo.private(`admin-sent-message-${this.guestList[i].id}`)
							.listenForWhisper('ChatEnd', (response) => {
								console.log('ChatEnd', response);
								let checkGuest = containsGuest(this.guestList, response) ;
								let currentGuestIndex = checkGuest.index;console.log(checkGuest)

								//bôi đen ô chat deleted
								if(this.guestList[currentGuestIndex] !== undefined) {
									Vue.set(this.guestList[currentGuestIndex],'isChatEnd', true);
								}

								Echo.leave(`admin-sent-message-${response.id}`)

						});



					}
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
		//console.log('containsGuest')

		//console.log(guestList)

		//console.log(obj);
		for( let i = 0; i < guestList.length; i ++ ) {console.log(guestList[i], obj.id)
			if(guestList[i].id === obj.id) {
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

<!--
(1): phải Echo.private().listen() trước thì Echo.private.whisper() sau mới dc. Ko sẽ error:
"Client event triggered before channel 'subscription_succeeded'"
