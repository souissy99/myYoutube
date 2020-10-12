<template>
<v-main>
  <v-row>
      <miniature
        :parentData="items"
      />
  </v-row>

  <v-row>
    <v-col cols="12">
    <v-row :justify="justifyEnd">
      <v-btn color="indigo" fab x-large dark class="ma-3 pa-6" @click="previous()">
            <v-icon>mdi-menu-left</v-icon>
      </v-btn> 
      <v-btn color="indigo" fab x-large dark class="ma-3 pa-6" @click="next()">
            <v-icon>mdi-menu-right</v-icon>
      </v-btn>
    </v-row>
    <v-row>
    </v-row>
    </v-col> 
  </v-row>
</v-main>
</template>

<script>
import axios from 'axios'
import miniature from '~/components/miniature.vue'

export default {
  components: {
    miniature,
  },
  data: () => ({
      justifyEnd: 'end',
      justifyStart: 'start',
      items: [],
      page: 0,
      perPage: 5
  }),
  created () {
      this.getVideos();
    },
  methods: {
      getVideos () {
        return axios.post(`http://localhost:8000/videos`, {
            page: this.page,
            perPage: this.perPage
          },
          )
          .then((res) => {
            this.items = res.data.data;
            console.log(res)
          })
          .catch((e) => {
            console.log(e)
          })
      },
      next () {
        if (this.items.length > 0) {
        this.page = this.page + 1;
        this.getVideos();
        }
      },
      previous () {
        if (this.page >= 0) {
          this.page = this.page - 1;
          this.getVideos();
        }
      },
    },
}
</script>
