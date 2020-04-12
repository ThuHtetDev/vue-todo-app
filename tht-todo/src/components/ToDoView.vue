<template>
    <!-- to_do list -->
    <div id="container" :class="{'dark_theme' : nightMode}">
        <div v-if="!nightMode"><img src="../assets/wcLogo.jpeg" class="logo"/></div>
        <div v-else><img src="../assets/logo.png"/></div>
        <div v-if="!nightMode" @click="nightMode = true">  <img src="../assets/night.svg"></div>
         <div v-else @click="nightMode = false">  <img src="../assets/light.svg"></div>
        <h1>&bull; Manage You Better &bull;</h1>
        <div class="underline">
        </div>
        <div id="contact_form">
            <div class="todo_fill">
                <input type="text"  id="todo-input" class="todo_input" v-model="newTodo" @keyup.enter="addTodo">
            </div>
            
            <div class="todo_containerBox">
                <div v-if="todoFilter == false" class="no_list">No more list Here :)</div>
                <div v-for="(todo,index) in todoFilter" :key="todo.id" class="todo-item">
                    <!-- item loop-->
                    <div class="add-item">
                        <input type="checkbox" v-model="todo.completed" @change="checkByOne(todo)" />
                        <span v-if="!todo.editing" class="todo-item-label" :class="{completed_line : todo.completed}">{{todo.title}}</span>
                        <span v-if="todo.editing">
                            <!-- inputBox code  -->
                                <input type="input" v-model="todo.title" class="form_field"/>
                            <!-- end inputBox code -->
                            <button @click="doneEdit(todo)">Done</button>
                            <button @click="cancelEdit(todo)">Cancel</button>
                        </span>
                        <span v-if="!todo.editing">
                            <button @click="editTodo(todo)">Edit</button>
                        </span>
                    </div> 
                    <div class="remove-item" @click="removeTodo(index,todo)">
                        x
                    </div>
                    <!-- end item-loop -->
                </div>
            </div>
             <div class="info_container">
                <div class="check_info">
                    <input type="checkbox" :checked="anyRemaining" @change="checkAllTodos()"  ref="rolesSelected"/> Check All
                </div>
                <div class="show_info">
                    <span v-if="remainings == 0"> Yay, I am done with this shit </span>
                   <span v-else> Fuck!, I have *{{remainings}} item left </span>
                </div>
            </div>
            <div class="filter_info">
                <button :class="{active : checker =='all'}" @click="checker='all'">All</button>
                <button :class="{active : checker =='active'}"  @click="checker='active'">Still Left</button>
                <button :class="{active : checker =='complete'}"  @click="checker='complete'">Complete</button>
                <button class="" v-if="showClearBtn" @click="clearComplete()">Clear Complete</button>
            </div>
        </div>
    </div>
    <!-- end of to_do list -->
   
</template>

<script>
import axios from 'axios'
axios.defaults.baseURL = 'http://localhost:8000/api/'

export default {
  name: 'todo-view',
  data () {
    return {
      msg: 'Thu Htet Tun',
      nightMode: false,
      newTodo: '',
      beforeEdit: '',
      checker: 'all',
      uncheckAll : false,
      todos: [],
    } //return data
  },
  watch: {
       	nightMode: function() {
			localStorage.setItem("nightMode", JSON.stringify(this.nightMode));
			console.log('Night Mode: ' + JSON.stringify(this.nightMode))
		}
  },
  created() {
      this.nightMode = JSON.parse(localStorage.getItem("nightMode"));
      axios.get('/todos').then(response => {
              this.todos = response.data
          })
          .catch(error => {
              console.log(error)
          })
  },
  computed: {
      remainings(){
          return this.todos.filter(todo => !todo.completed).length
      },
      anyRemaining(){
          if(this.remainings == 0  ){
                    return true
          }
         
      },
      todoFilter(){
          if(this.checker == 'all'){
             if(this.todos.length == 0){
                  return false
              }
              return this.todos
          }else if(this.checker == 'active'){
              if(this.todos.filter(t => !t.completed).length == 0){
                 return false
              }
              return this.todos.filter(t => !t.completed)
          }else if(this.checker == 'complete'){
              if(this.todos.filter(t => t.completed).length == 0){
                  return false
              }
              return this.todos.filter(t => t.completed)
          }
          return this.todos
      },
      showClearBtn(){
          return this.todos.filter(t=>t.completed).length > 0
      }
  },
  methods: {
      addTodo:function(){
          if(this.newTodo.trim().length == 0){
              return null;
          }
          //
            axios.post('/todos',{
                title: this.newTodo,
                completed: false
            }).then(response => {
                console.log('It is working')
                this.todos.push({
                    title: this.newTodo,
                    status: 'process',
                    editing : false,
                    completed : false
            })//push into array's object
                this.newTodo = ''
                this.idForTodo++
            })
            .catch(error => {
                console.log(error)
            })
          //
      },//add function
      removeTodo: function(index,todo){
           //
            axios.delete('/todos/' + todo.id).then(function (response) {
         
            })
            .catch(function (error) {
                console.log(error);            
            });
          //
           this.todos.splice(index,1)
      },
      editTodo: function(todo){
          this.beforeEdit = todo.title
          todo.editing = true
      },
      doneEdit: function(todo){
        if(todo.title.trim() == ''){
            todo.title = this.beforeEdit
        }
         //
            axios.put('/todos/' + todo.id, {
                title: todo.title,
                completed: todo.completed,
            })
            .then(function (response) {
                todo.editing = false
            })
            .catch(function (error) {
                console.log(error);            
            });
          //
      
      },
      cancelEdit: function(todo){
          todo.title = this.beforeEdit
          todo.editing = false
      },
      checkByOne: function(todo){
          //
            axios.put('/todos/' + todo.id, {
                title: todo.title,
                completed: todo.completed
            })
            .then(function (response) {
              
                 todo.editing = false
            })
            .catch(function (error) {
                console.log(error);            
            });
          //
      },
      checkAllTodos: function(){
            if(this.$refs.rolesSelected.checked == true) {
                    axios.put('/todosCheckAll',{
                        completed: true,
                    })
                    .then(function (response) {
                     
                    })
                    .catch(function (error) {
                        console.log(error);            
                    });
                this.todos.forEach(
                    (t) => t.completed = event.target.checked
                )
            }else{
                    axios.put('/todosCheckAll',{
                        completed: false,
                    })
                    .then(function (response) {
                   
                    })
                    .catch(function (error) {
                        console.log(error);      
                    });
                this.todos.forEach(
                    (t) => t.completed = event.target.checked = false
                )

            }
         
      },
      clearComplete: function(){
          this.todos = this.todos.filter(t => !t.completed)
      }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only --> 
<style>
    /* .container{
        width: 700px;
        margin: 0 auto;
    } */
    .dark_theme{
        background: #222;
        color: #fff;
    }
    .todo_input{
        width: 100%;
        padding: 10px 0px;
        font-size: 18px;
        margin-bottom: 19px;
        outline: none;
    }
    .todo-item{
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 10px 0px;
        background: #32AD76;
        padding:10px 0px;
        color: #fff;
    }
    .add-item{
        padding:0px 10px;
    }
    .remove-item{
        cursor: pointer;
        text-align: right;
        padding:0px 10px;
    }
    .completed_line{
        text-decoration: line-through;
        color: grey;
    } 
    .info_container{
        text-align: left;
        border-top: 1px solid grey;
    }
    .show_info,.check_info{
        padding: 5px 0px;
    }
    .active{
        background: #32AD76 !important;
    }
    .filter_info{
        text-align: left;
        margin-top: 10px;
    }
    .filter_info button{
        border: none;
        background: grey;
    }
    .no_list{
        background: grey;
        color: #fff;
        padding: 10px 0px;
        margin-bottom: 5px;
    }
   /* mobile_first */
    @import url(https://fonts.googleapis.com/css?family=Montserrat:400,700);

        html {
        font-family: 'Montserrat', Arial, sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        }
        body {
        /* background: #F2F3EB; */
        }
        button{
        color: #5A5A5A;
        font: inherit;
        margin: 0;
        }
        #container {
        border: solid 3px #474544;
        max-width: 768px;
        margin: 60px auto;
        position: relative;
        }
        #contact_form {
        padding: 37.5px;
        margin: 50px 0;
        }
        h1 {
        color: #474544;
        font-size: 32px;
        font-weight: 700;
        letter-spacing: 7px;
        text-align: center;
        text-transform: uppercase;
        }
        .underline {
        border-bottom: solid 2px #474544;
        margin: -0.512em auto;
        width: 80px;
        }
        .icon_wrapper {
        margin: 50px auto 0;
        width: 100%;
        }
        .icon {
        display: block;
        fill: #474544;
        height: 50px;
        margin: 0 auto;
        width: 50px;
        }

        input[type='text'] {
            background: none;
        border: none;
            border-bottom: solid 2px #474544;
            color: #474544;
            font-size: 1.000em;
        font-weight: 400;
        letter-spacing: 1px;
            margin: 0em 0 1.875em 0;
            padding: 0 0 0.875em 0;
            width: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -o-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            -ms-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        input[type='text']:focus, [type='email']:focus, textarea:focus {
            outline: none;
            padding: 0 0 0.875em 0;
        }
        .todo_fill {
        width: 100%;
        }
        ::-webkit-input-placeholder {
            color: #474544;
        }

        :-moz-placeholder { 
            color: #474544;
            opacity: 1;
        }

        ::-moz-placeholder {
            color: #474544;
            opacity: 1;
        }

        :-ms-input-placeholder {
            color: #474544;
        }

        #form_button:hover {
        background: #474544;
        color: #F2F3EB;
        }

        @media screen and (max-width: 768px) {
        #container {
            margin: 20px auto;
            width: 95%;
        }
        }

        @media screen and (max-width: 480px) {
        h1 {
            font-size: 26px;
        }
        
        .underline {
            width: 68px;
        }
        
        #form_button {
            padding: 15px 25px;
        }
        }

        @media screen and (max-width: 420px) {

        input[type='text'] {
            font-size: 0.875em;
        }
        }
   /* end mobile_first css */
</style>
