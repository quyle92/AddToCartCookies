<template id="context-menu-template">
    <div id="div-context-menu"  class="cls-context-menu" style="left: 160px; top: 57px"  v-if="menu===true">
        <ul>
            <li class="cls-context-menu-item">
               <a><img class="context-menu-icon" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0NS40IDQ1LjQiPjxwYXRoIGZpbGw9IiNmOTY5MGUiIGQ9Ik00MS4zIDE4LjZIMjYuOFY0YzAtMi0xLjgtNC00LTQtMi40IDAtNC4yIDItNC4yIDR2MTQuNkg0Yy0yIDAtNCAxLjgtNCA0IDAgMS4yLjUgMi4zIDEuMiAzIC44LjggMS44IDEuMyAzIDEuM2gxNC40djE0LjNjMCAxIC40IDIgMS4yIDMgLjcuNiAxLjggMSAzIDEgMi4yIDAgNC0xLjcgNC00VjI3aDE0LjVjMi4zIDAgNC0yIDQtNC4zcy0xLjgtNC00LTR6Ii8+PC9zdmc+" width="12">
                add new </a>
            </li>

            <li class="cls-context-menu-item">
                <a @click="removeItem($event)"><img class="context-menu-icon" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMzkuMiAzMzkuMiI+PHBhdGggZmlsbD0iI2Y5NjkwZSIgZD0iTTI0Ny4yIDE2OS42bDg0LTg0YzUuMy01LjMgOC0xMS43IDgtMTkuNCAwLTcuNi0yLjctMTQtOC0xOS40TDI5Mi40IDhDMjg3IDIuNyAyODAuNiAwIDI3MyAwYy03LjcgMC0xNCAyLjctMTkuNSA4bC04NCA4NEw4NS44IDhDODAuMyAyLjcgNzQgMCA2Ni4yIDBjLTcuNiAwLTE0IDIuNy0xOS40IDhMOCA0Ni44Yy01LjMgNS40LTggMTEuOC04IDE5LjQgMCA3LjcgMi43IDE0IDggMTkuNWw4NCA4NC04NCA4My44QzIuNyAyNTkgMCAyNjUuMyAwIDI3M2MwIDcuNiAyLjcgMTQgOCAxOS40bDM4LjggMzguOGM1LjQgNS4zIDExLjggOCAxOS40IDggNy43IDAgMTQtMi43IDE5LjUtOGw4NC04NCA4My44IDg0YzUuNCA1LjMgMTEuOCA4IDE5LjUgOCA3LjYgMCAxNC0yLjcgMTkuNC04bDM4LjgtMzguOGM1LjMtNS40IDgtMTEuOCA4LTE5LjUgMC03LjctMi43LTE0LTgtMTkuNWwtODQtODR6Ii8+PC9zdmc+" width="10">
                       remove </a>
            </li>

        </ul>
    </div>
</template>

<script>
export default {
    props:['isContextMenu', 'eventContextMenu', 'guestIndex', 'guestId'],
    data: function() {
        return {
            menu: false
        }
    },
    computed: {

    },
    methods: {
        show(e) {
            this.$nextTick( () => {
                let menu = document.getElementById("div-context-menu");
                menu.style.left = e.pageX + 'px'
                menu.style.top = e.pageY + 'px'
            });//(1)
            
              
        },
        removeItem() {
            // console.log(this.guestId);debugger
            let data = { guest_id: this.guestId };
            
            this.$emit('delete', data);
            
            
                
        }
    }, 
    watch: { 
        isContextMenu(val){
           this.menu = val;
           
        },
        eventContextMenu(val){
           this.show(val);
        }
    },
    mounted() {

    }
}
</script>

<!--Note
(1): nếu ko dùng this.$nextTick() thì DOM element sẽ chưa đc render => ko access đc  DOM element đó và error: [Vue warn]: Error in callback for watcher "eventContextMenu": "TypeError: menu is null"
-->

<style scoped>

.cls-context-menu {
   /*display:none;*/
   position:absolute;
}
.cls-context-menu ul, #context-menu li {
    list-style:none;
    margin:0; padding:0;
    background:white;
}
.cls-context-menu { border:solid 1px #CCC;}
.cls-context-menu li {
   border-bottom:solid 1px #CCC;
   display:block;
   padding:5px 12px;
   text-decoration:none;
   color:blue;
}
.cls-context-menu li:hover{
    background: blue;
    color: #FFF;
}
.cls-context-menu li:last-child { border:none; }

.context-menu-icon {
  top: 1px;
  position: relative;
  margin-right: 2px;
}
.cls-context-menu-item {
  cursor: pointer;
  display:block;
  padding:20px;
  background:#ECECEC;
}



</style>
