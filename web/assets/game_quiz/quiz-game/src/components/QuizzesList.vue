<template>
    <div id="list_quizzes" class="list_quizzes">
        <button class="btn btn-default" @click="requestQuizzesList">Test</button>
        <div class="count">
            <h3>All quizzes - {{ pagination.amount_items }}</h3>
        </div>
        <div id="postlist">
            <div class="panel" v-for="item in pagination.items">
                <div class="panel-heading">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-sm-9">
                                <h3 class="pull-left" style="color: black; font-family: 'Raleway', sans-serif;">{{ item.name }}</h3>
                            </div>
                            <div class="col-sm-3">
                                <h4 class="pull-right">
                                    <small>{{ item.date_of_create.date | date }}</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                    <div style="width: 100%">
                        {{ item.description }}
                    </div>
                    <div>
                        Users passed -
                        <!--{% if quiz.users_amount == null %}-->
                        <!--<b>0</b>-->
                        <!--{% else %}-->
                        <!--<b>{{ quiz.users_amount }}</b>-->
                        <!--{% endif %}-->
                    </div>
                    <div>
                        <!--{% if quiz.users_amount != null %}-->
                        <!--Best User - <b>{{ quiz.best_player }}</b> <{{ quiz.right_amount }}>-->
                        <!--{% endif %}-->

                    </div>
                </div>

                <div class="panel-footer" style="height: 50px; padding-top: 6px">

                    <!--{% if quiz.flag_passed %}-->
                    <!--<p class="pull-left label_info pass"> Passed </p>-->
                    <!--<p class="pull-left label_info pass"> Your result - {{ quiz.result }} </p>-->
                    <!--<a class="btn btn-info pull-right" href="{{ path('game_result', {'id_passage': quiz.id_passage}) }}">Result</a>-->
                    <!--{% else %}-->
                    <!--<p class="pull-left label_info nopass"> Not passed </p>-->
                    <!--<a class="btn btn-primary pull-right" href="{{ path('game', {'id_quiz': quiz.id_quiz}) }}">Start</a>-->
                    <!--{% endif %}-->

                </div>
            </div>

            <!--{% endfor %}-->
        </div>
        <!--{{ knp_pagination_render(pagination) }}-->
    </div>
</template>

<script>

    export default {
        name: 'RegistrationForm',
        data()
        {
            return {
                msg: 'Welcome to Your Vue App',

                request_quizzes_list: {
                    page: 1,
                    limit: 3,
                },

                pagination: {
                    items: {},
                    amount_items: 0,
                }
            }
        },
        methods: {
            requestQuizzesList()
            {
                if (this.$globalVariables.access_data.access_token) {
                    this.$http.get(
                      'http://quizzes.loc/api/quizzes',
                      {
                          params: this.request_quizzes_list,
                          headers: {
                              'Content-Type': 'application/json',
                              'Authorization': 'Bearer ' + this.$globalVariables.access_data.access_token,
                          },
                          emulateJSON: true
                      }
                    )
                      .then(
                        response => {
                            this.pagination.amount_items = response.body.quizzesAmount;
                            this.pagination.items = response.body.quizzes;

                            console.log(this.pagination);
                        },
                        error => {
                            this.$globalVariables.error_access = true;
                            console.error(error);
                        }
                      );
                }
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .list_quizzes {
        border: 2px solid grey;
        border-radius: 15px;
        padding: 10px;
        background-color: #384452;
        margin-bottom: 15px;
    }

    #list_quizzes .count h3 {
        color: white;
        margin-bottom: 15px;
    }

    .panel {
        background-color: white;
        border: 1px solid black;
        border-radius: 15px;
    }

    .label_info {
        border: 1px solid grey;
        border-radius: 5px;
        padding: 5px;
        margin-top: 0;
        margin-right: 3px;
        color: white;
    }

    .label_info.pass {
        background-color: green;
    }

    .label_info.nopass {
        background-color: #0b6ec4;
    }
</style>
