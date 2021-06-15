<template>
	<div>
      <div class="row mt-5">
          <div class="col-md-2 offset-md-1 mb-1">
            <button type="button" class="btn btn-info text-white" data-toggle="modal" data-target="#addUser">Add</button>
          </div>
          <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">User Info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form @submit.prevent="addUser()" @keydown="form.onKeydown($event)">
                  <AlertSuccess :form="form" message="Your changes have beend saved!" />
                <div class="modal-body">
                      <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">User Name</label>
                        <div class="col-sm-9">
                          <input type="text"  class="form-control" id="username" v-model="form.username">
                          <HasError :form="form" field="username" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                          <input type="text"  class="form-control" id="email" v-model="form.email">
                          <HasError :form="form" field="email" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="password" placeholder="Password" value='123'>
                          <HasError :form="form" field="password" /> 
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="passwordAgain" class="col-sm-3 col-form-label">Password Again </label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="passwordAgain" placeholder="Password Again" v-model="form.passwordAgain">
                          <HasError :form="form" field="passwordAgain" />
                        </div>
                      </div>
                 
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <Button :form="form" class="btn btn-primary">
                        Save
                  </Button>
                </div>
                 </form>
              </div>
            </div>
          </div>
          <div class="col-10 offset-md-1">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Responsive Hover Table</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Email</th>
                      <th>Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>183</td>
                      <td>John Doe</td>
                      <td>admin@gmail.com</td>
                      <td>admin</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
	</div>
</template>

<script>
import Form from 'vform'

export default {
  props:{

  },
  data: function() {
    return {
 		   form: new Form({
          username: 'admin',
          email: 'free2idol@gmail.com',
          password: '123',
          passwordAgain: '123'
        })
 	  }
  },
  methods: {
    async addUser() {
      let config = {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    
      const response = await this.form.post('/api/users', config);
    }
  }
}
</script>

<style scoped>

</style>