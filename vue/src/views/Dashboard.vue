<template>
  <PageComponent title="Dashboard">
    <div v-if="loading" class="flex justify-center">Loading...</div>
    <div
      v-else
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
    >
      <div
        class="bg-white shadow-md p-3 text-center flex flex-col animate-fade-in-down order-1 lg:order-2"
        style="animation-delay: 0.1s"
      >
        <h3 class="text-2xl font-semibold">Total Surveys</h3>
        <div
          class="text-8xl font-semibold flex-1 flex items-center justify-center"
        >
          {{ data.totalSurveys }}
        </div>
      </div>
      <div
        class="bg-white shadow-md p-3 text-center flex flex-col order-2 lg:order-4 animate-fade-in-down"
        style="animation-delay: 0.2s"
      >
        <h3 class="text-2xl font-semibold">Total Answers</h3>
        <div
          class="text-8xl font-semibold flex-1 flex items-center justify-center"
        >
          {{ data.totalAnswers }}
        </div>
      </div>
      <div
        class="row-span-2 animate-fade-in-down order-3 lg:order-1 bg-white shadow-md p-4"
      >
        <h3 class="text-2xl font-semibold">Latest Survey</h3>
        <img
          :src="data.latestSurvey.image_url"
          class="w-[240px] mx-auto"
          alt=""
        />
        <h3 class="font-bold text-xl mb-3">{{ data.latestSurvey.title }}</h3>
        <div class="flex justify-between text-sm mb-1">
          <div>Upload Date:</div>
          <div> 
            {{ moment(data.latestSurvey.created_at).format("DD-MM-YYYY hh:mm") }}
          </div>
        </div>
        <div class="flex justify-between text-sm mb-3">
          <div>Answers:</div>
          <div>{{ data.totalAnswers }}</div>
        </div>
        <div class="flex justify-between">
          <router-link
            :to="{ name: 'SurveyView', params: { id: data.latestSurvey.id } }"
            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-blue-500 hover:bg-blue-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
              />
            </svg>
            Edit Survey
          </router-link>
        </div>
      </div>
      <div
        class="bg-white shadow-md p-3 row-span-2 order-4 lg:order-3 animate-fade-in-down"
        style="animation-delay: 0.3s"
      >
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-2xl font-semibold">Latest Answers</h3>
        </div>
        <a
          href="#"
          v-for="answer of data.latestAnswers"
          :key="answer.id"
          class="block p-2 hover:bg-gray-100/90 cursor-pointer :bg-gray-200"
        >
          <div class="font-semibold">{{ answer.survey.title }}</div>
          <small
            >Answer Made at:
            <i class="font-semibold">{{ answer.end_date }}</i></small
          >
        </a>
      </div>
    </div>
  </PageComponent>
</template>

<script setup>
import { computed } from "@vue/reactivity";
import { useStore } from "vuex";
import PageComponent from "../components/PageComponents.vue";
import SurveyListItem from "../components/SurveyListItem.vue";
import moment from "moment";

const store = useStore();

const loading = computed(() => store.state.dashboard.loading);
const data = computed(() => store.state.dashboard.data);

store.dispatch("getDashboardData");


</script>

<style scoped></style>
