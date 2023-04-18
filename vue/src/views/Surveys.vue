<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
        <router-link :to="{ name: 'SurveyCreate' }" class="
                  flex py-2 px-4 border border-transparent text-sm rounded-md text-white bg-green-600 hover:ng-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500
                ">Addn New Survey
                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg> -->
          </router-link>
      </div>
    </template>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
      <SurveyListItem  v-for="(survey, ind) in surveys" :key="survey.id"  
      class="opacity-0 animate-fade-in-down"
          :style="{ animationDelay: `${ind * 0.1}s` }"
       :survey="survey" @delete="deleteSurvey(survey)"/>
    </div>
  </PageComponent>
</template>

<script setup>
import store from "../store";
import { computed } from "vue";
import PageComponent from "../components/PageComponents.vue";
import SurveyListItem from "../components/SurveyListItem.vue";

const surveys = computed(() => store.state.surveys.data);

store.dispatch('getSurveys');



function deleteSurvey(survey){
  if(
    confirm(`Do you want to delete this survey? Operation can'be undone!`)
    ){
    //delete survey
    store.dispatch("deleteSurvey",survey.id).then(()=>{
      store.dispatch("getSurveys");
      // router.push({
      //   name: "Surveys",
      // });
    });
    } 
}

</script>
