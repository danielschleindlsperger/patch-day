<template>
    <v-dialog v-model="isOpen" max-width="640">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create company</h2>
            </v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-text-field label="Name" required
                                  v-model="company.name"/>
                    <v-layout row justify-space-between align-baseline
                              class="logo-upload-wrapper mb-4">
                        <span class="file-name" v-if="fileName">{{ fileName }}
                        </span>
                        <upload-button title="Select Logo"
                                       :selectedCallback="fileSelected"
                                        :class="{ green : fileName }">
                        </upload-button>
                    </v-layout>
                    <small>*indicates required field</small>
                </v-container>
            </v-card-text>
            <v-card-actions justify-end>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createCompany()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'
  import UploadButton from 'components/UploadButton'

  export default {
    name: 'create-company',
    components: {
      UploadButton,
    },
    data() {
      return {
        isOpen: false,
        company: {
          name: '',
          logo: null
        },
        fileName: '',
      }
    },
    mounted () {
      eventBus.$on('company.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createCompany() {
        repo.company.create(this.company).then(() => {
          this.isOpen = false
          this.company.name = ''
          delete this.company.logo
        })
      },
      fileSelected(file) {
        if (file) {
          this.company.logo = file
          this.fileName = file.name
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
    .file-name {
        max-width: 5em;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>