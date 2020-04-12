<template>
  <div id="app">
    
    <ToDoView/>
  </div>
</template>

<script>
import HelloWorld from './components/HelloWorld'
import ToDoView from './components/ToDoView'

export default {
  name: 'App',
  components: {
    HelloWorld,
    ToDoView
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
.logo{
  width: 300px;
  height: 200px;
}
.mode{
  display: block;
}
</style>
