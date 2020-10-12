<template>
<v-main>
    <v-snackbar
      v-model="snackbar"
      :multi-line="multiLine"
      top="top"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="indigo"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <v-card
    class="mx-auto"
    outlined
    width="100%"
  >
    <v-list-item three-line>
                <v-card-title>
                  <span class="headline">Upload</span>
                </v-card-title>
    </v-list-item>

    <v-card-text>
    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >
      <v-text-field
        v-model="name"
        color="indigo"
        label="Donnez un titre à votre vidéo"
        :rules="nameRules"
        required
      ></v-text-field>
  
    <v-file-input
      accept="video/*"
      class="pa-4"
      v-model="files"
      color="indigo accent-4"
      counter
      label="Video"
      multiple
      placeholder="Choisissez votre vidéo"
      prepend-icon="mdi-video"
      outlined
      required
      :rules="videoRules"
      :show-size="1000"
    >
      <template v-slot:selection="{ index, text }">
        <v-chip
          v-if="index < 2"
          color="deep-purple accent-4"
          dark
          label
          small
        >
          {{ text }}
        </v-chip>
  
        <span
          v-else-if="index === 2"
          class="overline grey--text text--darken-3 mx-2"
        >
          +{{ files.length - 2 }} File(s)
        </span>
      </template>
    </v-file-input>

    </v-form>
    </v-card-text>

    <v-card-actions>
        <v-btn 
        :disabled="!valid"
        color="indigo" 
        @click="validate()"
        text
        >Poster
        </v-btn>
    </v-card-actions>
  </v-card>
</v-main>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

  export default {
    computed: mapGetters({
    user: 'auth/getUser'
    }),
    data: () => ({
      valid: true,
      files: [],
      videoRules: [
      v => !!v || 'Veuillez choisir une video',
      ],
      name: '',
      nameRules: [
      v => !!v || 'Titre obligatoire',
      ],
      select: null,
      form: {},
      multiLine: true,
      snackbar: false,
      text: 'La video à bien été postée !',
    }),

    methods: {
      validate () {

        if (this.files) {
            let formData = new FormData();
            for (let file of this.files) {
                formData.append("source", file, file.name);
            }
            formData.append("name", this.name);
            formData.append("user_id", this.user[1].id);
            this.form = formData;
        }
        console.log(this.files[0])
        return axios.post(`http://localhost:8000/user/${this.user[1].id}/videoUpload`, this.form,
        {
        headers: {
                'Authorization': 'Bearer' + this.user[0],
                'Content-Type': 'application/x-www-form-urlencoded'
         }
        }
        )
        .then((res) => {
            if (res.status === 201) {
                this.snackbar = true;
            }
        })
        .catch((e) => {
            console.log(e)
        })
      },
    },
  }
</script>
