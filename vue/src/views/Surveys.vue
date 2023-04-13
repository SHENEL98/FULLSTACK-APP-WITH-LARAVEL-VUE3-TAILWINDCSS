<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Surveys</h1>
        <router-link :to="{ name: 'SurveyCreate' }" class="py-2 px-8 text-wh
           bg-emerald-500 rounded-md hover:bg-emrald-600">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add new Survey</router-link>
      </div>
    </template>
    <div>
      <pre>{{ surveys }}</pre>
    </div>
  </PageComponent>
</template>

<script setup>
import store from "../store";
import { computed } from "vue";
import PageComponent from "../components/PageComponents.vue";

const surveys = computed(()=> store.state.surveys );

</script>
