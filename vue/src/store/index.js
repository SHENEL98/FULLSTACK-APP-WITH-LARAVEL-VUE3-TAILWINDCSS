import { result } from "lodash";
import { createStore } from "vuex";
import axiosClient from "../axios";

const tmpSurveys = [
    {
        id: 100,
        title: "TheCodeholic YouTube channel content",
        slug: "thecodeholic-youtube-channel-content",
        status: "draft",
        image: "https://pbs.twimg.com/profile_images/1118059535003017221/9ZwEYqj2_400x400.png",
        description:
            "My name is Zura. <br>I am Web Developer with 9+ years of experience, free educational",
        created_at: "2021-12-20  18: 00: 00",
        updated_at: "2021-12-20 18:00:00",
        expire_date: "2021-12-31 18:00:00",
        questions: [
            {
                id: 1,
                type: "select",
                question: "From which country are you?",
                description: null,
                data: {
                    options: [
                        {
                            uuid: "f8af96F2-1d80-4632-9e9e-b560678e52ea",
                            text: "USA",
                        },
                        {
                            uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440",
                            text: "Georgia",
                        },
                        {
                            uuid: "b5c09733-a49e-460a-ba8a-526863010729",
                            text: "Germany",
                        },
                        {
                            uuid: "2abficee-f5fb-427c-a220-b5d159ad6513",
                            text: "India",
                        },
                    ],
                },
            },
            {
                id: 2,
                type: "checkbox",
                question: "Which language videos do you want to see on my channel?",
                description: "This a description for my project....",
                data: {
                    options: [
                        {
                            uuid: "f8af96F2-1d80-4632-9e9e-b560678e52ea",
                            text: "Javascript",
                        },
                        {
                            uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440",
                            text: "HTML + CSS",
                        },
                        {
                            uuid: "b5c09733-a49e-460a-ba8a-526863010729",
                            text: "All of the above",
                        }, 
                    ],
                },
            },
            {
                id: 3,
                type: "radio",
                question: "Which Laravel verion you prefer most?",
                description: "This a description for my project....",
                data: {
                    options: [
                        {
                            uuid: "f8af96F2-1d80-4632-9e9e-b560678e52ea",
                            text: "Laravel 8",
                        },
                        {
                            uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440",
                            text: "Laravel 9",
                        },
                        {
                            uuid: "b5c09733-a49e-460a-ba8a-526863010729",
                            text: "Laravel 10",

                        }, 
                    ],
                },
            },
            {
                id: 4,
                type: "text",
                question: "Whatis your favourite youtube channel?",
                description: null,
                data: {},
            },
            {
                id: 5,
                type: "textarea",
                question: "What you think about working as laravel developer ?",
                description: "Write your honest option",
                data: {},
            },

        ],
    },
    {
        id: 200,
        title: "Title 2",
        slug: "slug 2",
        status: "draft",
        image: "https://pbs.twimg.com/profile_images/1118059535003017221/9ZwEYqj2_400x400.png",
        description:
            "description 2 ",
        created_at: "2021-12-22  18: 00: 00",
        updated_at: "2021-12-22 18:00:00",
        expire_date: "2021-12-31 18:00:00",
        questions: [],
    },
    {
        id: 300,
        title: "Title 3",
        slug: "slug 3",
        status: "draft",
        image: "https://pbs.twimg.com/profile_images/1118059535003017221/9ZwEYqj2_400x400.png",
        description: "description 3",
        created_at: "2021-12-23  18: 00: 00",
        updated_at: "2021-12-23 18:00:00",
        expire_date: "2021-12-31 18:00:00",
        questions: [],
    },
];

//create store variable
const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem("TOKEN"),
        },
        surveys: [...tmpSurveys],
    },
    getters: {},
    actions: {
        register({ commit }, user) {
            return axiosClient.post("/register", user).then(({ data }) => {
                commit("setUser", data);
                return data;
            });
        },
        login({ commit }, user) {
            return axiosClient.post("/login", user).then(({ data }) => {
                commit("setUser", data);
                return data;
            });
        },
        logout({ commit }) {
            return axiosClient.post("/logout").then((response) => {
                commit("logout");
                return response;
            });
        },
    },
    mutations: {
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
        },
        //setUser invitation
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem("TOKEN", userData.token);
        },
    },

    modules: {},
});

export default store;
