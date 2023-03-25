import { createStore } from "vuex";

//create store variable
const store = createStore({
    state:{
        user:{
            data:{name:'Zara'},
            token:null
        }
    },
    getters:{},
    actions:{},
    mutations:{},
    modules:{}
})

export default store;