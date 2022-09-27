<template>
    <div>
      Login User
      <button @click="login">Login</button>
    </div>
</template>

<script>
export default {
    name: "LoginUser",
    components: {

    },
    data(){
    return {
        user:{
            name:"vanmanh",
        }
    }
    },
    methods: {
        login:function(){
            window.localStorage.setItem('user',JSON.stringify(this.user));
        }
    }
}
</script>