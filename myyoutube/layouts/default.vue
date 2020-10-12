<template>
  <v-app dark>
    <v-main>
      <v-container>
        <v-toolbar>
          <v-toolbar-title>
            <div class="my-2">
              <v-btn text x-large color="white" nuxt to="/">my Youtube</v-btn>
            </div>
          </v-toolbar-title>

          <v-col cols="8" sm="4">
            <v-text-field
              color="indigo"
              single-line
              hide-details
              label="Recherchez..."
              prepend-icon="mdi-magnify"
            ></v-text-field>
          </v-col>

          <v-col>
            <v-btn class="mx-2" fab dark small color="indigo" nuxt to="/upload">
              <v-icon dark>mdi-arrow-up-bold-box-outline</v-icon>
            </v-btn>
          </v-col>

          <v-row justify="center" v-if="!connected">
            <v-dialog v-model="register" persistent max-width="600px">
              <template v-slot:activator="{ on, attrs }">
              <v-col cols="8" sm="4">
                <v-btn
                  class="ma-2"
                  outlined
                  color="indigo"
                  medium
                  v-bind="attrs"
                  v-on="on"
                  >Inscritpion</v-btn
                >
              </v-col>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">Inscription</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="8" sm="6" md="4">
                        <v-text-field
                          label="Username*"
                          color="indigo"
                          required
                          v-model="username"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="6" md="4">
                        <v-text-field
                          label="Pseudo"
                          color="indigo"
                          v-model="pseudo"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-text-field 
                        label="Email*" 
                        color="indigo"
                        required
                        v-model="email"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-text-field
                          label="Password*"
                          color="indigo"
                          type="password"
                          required
                          v-model="password"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-text-field
                          label="Confirmation mot de passe*"
                          color="indigo"
                          type="password"
                          required
                          v-model="passwordConfirmation"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                  <small>*Champs obligatoires</small>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="indigo" text @click="register = false"
                    >Fermer</v-btn
                  >
                  <v-btn color="indigo" text @click="signin()"
                    >Inscription</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>

          <v-row justify="center" v-if="!connected">
            <v-dialog v-model="login" persistent max-width="600px">
              <template v-slot:activator="{ on, attrs }">
              <v-col cols="8" sm="4">
                <v-btn
                  class="ma-2"
                  color="indigo"
                  medium
                  v-bind="attrs"
                  v-on="on"
                  >Connexion</v-btn
                >
              </v-col>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">Connexion</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field 
                        color="indigo"
                        label="Pseudo*"
                        required 
                        v-model="username"></v-text-field>
                      </v-col>
                      <v-col cols="12">
                        <v-text-field
                        color="indigo" 
                        label="Mot de passe*" 
                        required 
                        v-model="password"
                        type="password"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                  <small>*Champs obligatoires</small>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="indigo" text @click="login = false"
                    >Fermer</v-btn
                  >
                  <v-btn color="indigo" text @click="auth()"
                    >Connexion</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>

          <v-flex class="text-xs-right" v-else>
          <div>
                <v-btn
                  color="indigo"
                  medium
                  @click="logout()"
                  >Mon compte</v-btn
                >
          </div>
          </v-flex>

        </v-toolbar>
      </v-container>
        <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    </v-main>
    <v-footer :absolute="!fixed" app>
      <span
        >myYoutube&copy; {{ new Date().getFullYear() }} by souiss_y, habi_a,
        courta_f</span
      >
    </v-footer>
  </v-app>
</template>

<script>
import axios from 'axios'
import { mapMutations, mapGetters } from 'vuex'

export default {
  computed: mapGetters({
    connected: 'auth/getStatus',
    user: 'auth/getUser'
  }),
  data: () => ({
      username: '',
      password: '',
      email: '',
      passwordConfirmation: '',
      pseudo: '',
      register: false,
      login: false,
      clipped: false,
      drawer: false,
      fixed: false,
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Vuetify.js',
  }),
  methods: {
    auth() {
      return axios.post(`http://localhost:8000/auth`, {
        username: this.username,
        password: this.password
      },
      )
      .then((res) => {
        if (res.status === 201) {
          axios.get('http://localhost:8000/currentUser', {
            headers: {
              'Authorization': 'Bearer' + res.data.data,
            }
          })
          .then((data) => {
              if (data.status === 200) {
                this.addUserToStore(res.data.data);
                this.addUserToStore(data.data.user);
                console.log(this.user)
              }
          })

        }
      })
      .catch((e) => {
        console.log(e)
      })
    },

     signin() {
      return axios.post(`http://localhost:8000/user`, {
        username: this.username,
        email: this.email,
        password: this.password,
        password_confirmation: this.passwordConfirmation
      },
      )
      .then((res) => {
        this.register = false;
        this.login = true;
        console.log(res);
      })
      .catch((e) => {
        console.log(e)
      })
    },
    addUserToStore (data) {
      if (data) {
        this.$store.commit('auth/add', { data })
        this.login = false;
      }
    },
    ...mapMutations({
      logout: 'auth/logout'
    })
  }
}
</script>
