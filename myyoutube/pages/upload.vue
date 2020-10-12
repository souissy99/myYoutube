<template>
        <v-row v-if="!connected">
          <notLog />
        </v-row>
        <v-row v-else>
          <uploadForm/>
        </v-row>
</template>

<script>
import axios from 'axios'
import notLog from '~/components/uploadNotLog.vue'
import uploadForm from '~/components/uploadForm.vue'
import { mapMutations, mapGetters } from 'vuex'

  export default {
  computed: mapGetters({
    connected: 'auth/getStatus'
  }),
  components: {
    notLog,
    uploadForm
  },
  
  data: () => ({
        
    }),
  }
</script>