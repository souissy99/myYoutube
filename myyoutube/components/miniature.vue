<template>
<v-container class="pa-4 text-center">
      <v-row class="fill-height" align="center" justify="center">
        <template v-for="(item, i) in parentData">
          <v-col
            :key="i"
            cols="12"
            md="4"
          >
            <v-hover v-slot:default="{ hover }">
              <v-card
                :to="{ name: 'video', params: { info: item } }"
                :elevation="hover ? 12 : 2"
                :class="{ 'on-hover': hover }"
              >
                <v-img
                  :src="require('../assets/hebergement-video.jpg')"
                  height="225px"
                >
                  <v-card-title class="title white--text">
                    <v-row
                      class="fill-height flex-column"
                      justify="space-between"
                    >
                      <p class="mt-4 subheading text-left">{{ item.name }}</p>
  
                      <div>
                        <p class="ma-0 body-1 font-weight-bold font-italic text-left">
                          {{ item.view }} vue(s)
                        </p>
                      </div>
  
                      <div class="align-self-center">
                        <v-btn
                          v-for="(icon, index) in icons"
                          :key="index"
                          :class="{ 'show-btns': hover }"
                          color="transparent"
                          icon
                        >
                          <v-icon
                            :class="{ 'show-btns': hover }"
                            color="transparent"
                          >
                            {{ icon }}
                          </v-icon>
                        </v-btn>
                      </div>
                    </v-row>
                  </v-card-title>
                </v-img>
              </v-card>
            </v-hover>
          </v-col>
        </template>
      </v-row>
    </v-container>  
</template>

<script>
  export default {
    props: {
      parentData: Array
    },
    data: () => ({
      icons: ['mdi-play'],
      transparent: 'rgba(255, 255, 255, 0)',
    }),
  }
</script>

<style scoped>
.v-card {
  transition: opacity .4s ease-in-out;
}

.v-card:not(.on-hover) {
  opacity: 0.6;
 }

.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>