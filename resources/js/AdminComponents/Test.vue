<template>
	<div>

	</div>
</template>

<script>
export default {
  props:['isContextMenu', 'eventContextMenu'],
  data: function() {
    return {
 		
 	}
  },
  computed: {
    event() {
        return this.eventContextMenu;
    }
  },
  methods: {

  }, 
   watch: { 

        eventContextMenu(val){
            console.log('test: ' +val)
        }
    },
  mounted() {
 
  }
}
</script>


<style scoped>

</style>
