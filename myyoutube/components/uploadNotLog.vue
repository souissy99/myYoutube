<template>
    <v-img :src="require('../assets/giphy.gif')" aspect-ratio="2">
            <div align="center">
              <v-row class="lightbox white--text pa-2 fill-height">
                <v-col>
                  <v-sheet
                    class="px-4 pt-4 pb-3"
                    style="cursor: pointer;"
                  >
                    <div
                      :class="[`text-h2`]"
                      class="transition-swing indigo--text font-weight-small"
                    >
                      Vous devez être connecté pour poster une video.
                    </div>
                  </v-sheet>
                </v-col>
              </v-row>
            </div>
    </v-img>
</template>