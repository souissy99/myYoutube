<template>
  <v-card
    class="mx-auto"
  >
<vue-plyr>
  <video>
    <source :src="require(`/app/video/360p${ this.videoPath }`)" type="video/mp4" size="360">
    <source :src="require(`/app/video/480p${ this.videoPath }`)" type="video/mp4" size="480">
    <source :src="require(`/app/video/720p${ this.videoPath }`)" type="video/mp4" size="720">
    <source :src="require(`/app/video/1080p${ this.videoPath }`)" type="video/mp4" size="1080">
    <track kind="captions" label="English" srclang="en"  default>
  </video>
</vue-plyr>

    <v-card-title>
      {{ this.video.name }}
    </v-card-title>

    <v-card-subtitle>
      {{ this.video.view }} vue(s)
    </v-card-subtitle>

    <v-card-actions>

      <v-dialog v-model="comment" persistent max-width="600px">
              <template v-slot:activator="{ on, attrs }">
                    <v-btn
                    color="indigo"
                    v-bind="attrs"
                    v-on="on"                    
                    text
                    >
                    Commenter
                    </v-btn>
              </template>
              <v-card v-if="connected">
                <v-card-title>
                  <span class="headline">Commentaire</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field 
                        color="indigo"
                        label="Votre commentaire"
                        required 
                        v-model="commentVideo"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="white" text @click="comment = false"
                    >Fermer</v-btn
                  >
                  <v-btn color="indigo" text @click="postComment()"
                    >Valider</v-btn
                  >
                </v-card-actions>
              </v-card>
              <v-card v-else>
                <v-card-title>
                  <span class="headline">Commentaire</span>
                </v-card-title>
                <v-card-text>
                    <span class="headline">Vous devez être connecté pour poster un commentaire</span>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="white" text @click="comment = false"
                    >Fermer</v-btn
                  >
                </v-card-actions>
              </v-card>
          </v-dialog>

      <v-spacer></v-spacer>

<v-dialog v-model="settings" persistent max-width="600px">
              <template v-slot:activator="{ on, attrs }">
                    <v-btn
                    v-if="currentUser"
                    v-bind="attrs"
                    v-on="on"                    
                    icon
                    >
                        <v-icon>mdi-cog</v-icon>
                    </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">Paramètres</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field 
                        color="indigo"
                        :label=this.video.name
                        required 
                        v-model="videoName"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="white" text @click="settings = false"
                    >Fermer</v-btn
                  >
                  <v-btn color="indigo" text @click="updateVideo()"
                    >Valider</v-btn
                  >
                 <v-btn color="error" text @click="deleteVideo()"
                    >Supprimer</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-dialog>

      <v-btn
        icon
        @click="show = !show"
      >
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
      </v-btn>
    </v-card-actions>

    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>
        <v-card-title>
        Commentaire(s)
        </v-card-title>
        <template v-for="(item, i) in allComment">
          <v-card-text :key="i">
            {{ item.body }}
          </v-card-text>
          <v-spacer></v-spacer>
          <v-card-text :key="i">
            by {{ item.user_id }}
          </v-card-text>
          <v-divider></v-divider>
        </template>
      <v-row>
      <v-btn color="indigo" fab small dark class="ma-3 pa-6" @click="previous()">
            <v-icon>mdi-menu-left</v-icon>
      </v-btn> 
      <v-btn color="indigo" fab small dark class="ma-3 pa-6" @click="next()">
            <v-icon>mdi-menu-right</v-icon>
      </v-btn>
    </v-row>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
const path = require('path');


  export default {
    computed: {
      ...mapGetters({
      video: 'video/getVideo',
      user: 'auth/getUser',
      connected: 'auth/getStatus'
      })
    },
    data: () => ({
        allComment: [],
        videoPath: '',
        videoName: '',
        commentVideo: '',
        settings: false,
        comment: false,
        currentUser: false,
        show: false,
        form: {},
        commentForm: {},
        page: 0,
        perPage: 5
    }),

    created() {
      console.log(this.currentUser)
        this.checkUser();
        let data;
        data = this.$route.params.info;
        this.$store.commit('video/add', { data })

        this.videoPath = path.basename(this.video.source);

      axios.put(`http://localhost:8000/video/${this.video.id}`, {
            view: this.video.view + 1,
          },
          )
          .then((res) => {
            console.log(res)
          })
          .catch((e) => {
            console.log(e)
          })  
          this.getComment(); 
    },

    methods: {
        checkUser() {
            if (this.user) {
                if (this.user.id = this.video.user_id)
                    this.currentUser = true;
            } else 
              this.currentUser = false;

        },

        getComment() {
          let formData = new FormData();
          formData.append("page", this.page);
          formData.append("perPage", this.perPage);
          this.commentForm = formData;
        return axios.post(`http://localhost:8000/video/${this.video.id}/comments`, this.commentForm,
          {
              headers: {
                  'Authorization': 'Bearer' + this.user[0],
                  'Content-Type': 'application/x-www-form-urlencoded'
            }
          }
          )
          .then((res) => {
            this.allComment = res.data.data
          })
          .catch((e) => {
            console.log(e)
          }) 

        },

        deleteVideo() {
          return axios.delete(`http://localhost:8000/video/${this.video.id}`,  {
            headers: {
                'Authorization': 'Bearer' + this.user[0],
                'Content-Type': 'application/x-www-form-urlencoded'
            }
          }       
          )
          .then((res) => {
            this.show = false;
            this.$router.push("/")
          })
          .catch((e) => {
            console.log(e)
          })  
        },

        updateVideo() {
          return axios.put(`http://localhost:8000/video/${this.video.id}`, {
            name: this.videoName,
          },
          )
          .then((res) => {
            this.show = false;
            this.$router.push("/")
          })
          .catch((e) => {
            console.log(e)
          })
        },

        postComment() {
          let formData = new FormData();
          formData.append("body", this.commentVideo);
          this.form = formData;

            return axios.post(`http://localhost:8000/video/${this.video.id}/comment`, this.form,
          {
          headers: {
                  'Authorization': 'Bearer' + this.user[0],
                  'Content-Type': 'application/x-www-form-urlencoded'
          }
          }
          )
          .then((res) => {
              console.log(res)
          })
          .catch((e) => {
              console.log(e)
          })
        },
      next () {
        if (this.allComment.length > 0) {
        this.page = this.page + 1;
        this.getComment();
        }
      },
      previous () {
        if (this.page >= 0) {
          this.page = this.page - 1;
          this.getComment();
        }
      },

    }
  }
</script>