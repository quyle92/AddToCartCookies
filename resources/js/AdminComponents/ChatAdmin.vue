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
								<input type="text" class="search-bar"  placeholder="Search"
								 v-model="searchString">
							</div>
						</div>
					</div>
					<div class="inbox_chat">
						<div class="chat_list" style="height: 400px; overflow-y: scroll;">
							<div class="chat_people"
								:class="[{active_chat:guest.active}, {'guest-leave-chat': guest.isChatEnd}]"
								v-for="(guest, index) in filterGuestList"
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
						<div class="incoming_msg_img" v-if="isObjEmpty(selectedGuest) || selectedGuest.chat.length === 0">
						</div>
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
				deletedChatId:'',
				searchString:''
			}
		},
		computed: {
			...mapState([
					'selectedGuest', 'selectedGuestIndex',
			]),
			filterGuestList: function() {
				return this.guestList.filter( guest => {
					return guest.name.toLowerCase().match(this.searchString)
				})
			}
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
		              console.log('AdminSentMessage')
		          	});

			},
			closeContextMenu() {
					this.isContextMenu = false;

			},
			deleteChat(data){
				Echo.private(`admin-sent-message-${this.guestId}`)
					.whisper('ChatEndFromAdmin',{id: this.guestId});

				let guestIndex = this.guestIndex,
					guestSelected = this.guestList[guestIndex]

				this.guestList.splice(guestIndex, 1);
				axios.delete('/api/deleteChat', { data: data })
                	.then( (response) => {
                 		//this.$store.commit('SET_SELECTED_GUEST', '');
						// this.$store.commit('SET_SELECTED_GUEST_INDEX', '');
						

            		}).catch( (error) => {
                		alert(error.response.data.message);
                		this.guestList.splice(guestIndex, 0, guestSelected)
            		});


			},
			deleteChatAll(){
				for (var i = 0; i < this.guestList.length; i++) {
					let id = this.guestList[i].id;
					Echo.private(`admin-sent-message-${id}`)
					.whisper('ChatEndFromAdmin',{id: id});
				}
				let guestList = this.guestList;
				this.guestList = [];
				axios.delete('/api/deleteChatAll')
                	.then( (response) => {
                		this.$store.commit('SET_SELECTED_GUEST', '');
						this.$store.commit('SET_SELECTED_GUEST_INDEX', '');

						

            		}).catch( (error) => {
                		alert(error.response.data.message);
                		this.guestList = guestList
            		});



			},
			selectGuest(guest, index, e) {
				guest.isRead = true;
				this.$store.commit('SET_SELECTED_GUEST', guest);
				this.$store.commit('SET_SELECTED_GUEST_INDEX', index);
				this.guestList.map( e => {

					e.active = false;

				});
				this.guestList[index].active = true;

				Echo.private(`admin-sent-message-${this.selectedGuest.id}`)
					.listenForWhisper('typing', (e) => {
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
				if( this.message === ''  ) return;
				let message = this.message;
				this.message = '';

				this.guestList[this.selectedGuestIndex].chat.push({
					user: 'admin',
					content: message
				});

				this.$store.commit('SET_SELECTED_GUEST', this.guestList[this.selectedGuestIndex]);
				//remove typing notification on guest side
				this.type();
				
				axios.post('/adminSentMessage', {
					guest_id: this.selectedGuest.id,
					guest: this.selectedGuest.name,
					message: message,
				})
				.then( (response) => {
					
				})
				.catch( (error) => {
					console.log(error);
					this.isError = true
					alert('Errors. Please fix it!')
				});

				

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

			// let adminId = $('meta[name="admin-id').attr('content');
			// Echo.private('App.User.' +  adminId).notification((notification) => {
	  //       	// console.log('notification: ', notification);
	  //   	});



			vm.$on('closeContextMenu', () => {
				this.isContextMenu = false
			});

		},
		created() {

			axios.get('/api/getGuestList')
				.then( (response) => {
					// console.log(response.data)
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
								let checkGuest = containsGuest(this.guestList, response) ;
								let currentGuestIndex = checkGuest.index;

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
		for( let i = 0; i < guestList.length; i ++ ) {
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

<style scoped lang="scss">
 @import "../../sass/chat-admin.css";

</style>

<!--
(1): phải Echo.private().listen() trước thì Echo.private.whisper() sau mới dc. Ko sẽ error:
"Client event triggered before channel 'subscription_succeeded'"
