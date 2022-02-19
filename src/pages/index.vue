<template>
  <v-card>
    <hello-world></hello-world>
  </v-card>
</template>

<script>
import HelloWorld from "../components/HelloWorld.vue";
// @ is an alias to /src
export default {
    layout: "default",
    components: { HelloWorld }
}
</script>
