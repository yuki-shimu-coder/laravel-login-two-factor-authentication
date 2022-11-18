<html>

<head>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div id="app" class="p-5">
    <div class="alert alert-info" v-if="message" v-text="message"></div>

    <!-- １段階目のログインフォーム -->
    <div v-if="step==1">
      <div class="form-group">
        <label>メールアドレス</label>
        <input type="text" class="form-control" v-model="email">
      </div>
      <div class="form-group">
        <label>パスワード</label>
        <input type="password" class="form-control" v-model="password">
      </div>
      <button type="button" class="btn btn-primary" @click="firstAuth">送信する</button>
    </div>

    <!-- ２段階目・ログインフォーム -->
    <div v-if="step==2">
      ２段階認証のパスワードをメールアドレスに登録しました。（有効時間：10分間）
      <hr>
      <div class="form-group">
        <label>２段階パスワード</label>
        <input type="text" class="form-control" v-model="token">
      </div>
      <button type="button" class="btn btn-primary" @click="secondAuth">送信する</button>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
  <script>
    new Vue({
            el: '#app',
            data: {
                step: 1,
                email: '',
                password: '',
                token: '',
                userId: -1,
                message: ''
            },
            methods: {
                firstAuth() {

                    this.message = '';

                    const url = '/ajax/two_factor_auth/first_auth';
                    const params = {
                        email: this.email,
                        password: this.password
                    };
                    axios.post(url, params)
                        .then(response => {

                            const result = response.data.result;

                            if(result) {

                                this.userId = response.data.user_id;
                                this.step = 2;
                              console.log('this.userId',this.userId)
                            } else {

                                this.message = 'ログイン情報が間違っています。';

                            }

                        });

                },
                secondAuth() {
                    console.log(this.token)
                    const url = '/ajax/two_factor_auth/second_auth';
                    const params = {
                        user_id: this.userId,
                        two_factor_token: this.token
                    };

                    axios.post(url, params)
                        .then(response => {

                            const result = response.data.result;

                            if(result) {

                                // ２段階認証成功
                                location.href = '/dashboard';

                            } else {

                                this.message = '２段階パスワードが正しくありません。';
                                this.token = '';

                            }

                        });

                }
            }
        });

  </script>
</body>

</html>