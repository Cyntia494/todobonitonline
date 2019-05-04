{extends file='page.tpl'}

{block name='page_content'}
  <div class="section-login password">
    <div class="password-content">
        <div class="forgot-password">
            <h2>
              {l s='Forgot your password?' d='Shop.Theme.Customeraccount'}
            </h2>
            <form action="{$urls.pages.password}" method="post">

              <section class="form-fields renew-password">

                <div class="email">
                  {l
                    s='Email address: %email%'
                    d='Shop.Theme.Customeraccount'
                    sprintf=['%email%' => $customer_email|stripslashes]}
                </div>

                <div class="container-form">
                  <div class="form-group">
                    <label class="form-control-label">{l s='New password' d='Shop.Forms.Labels'}</label>
                    <div class="">
                      <input class="form-control" type="password" data-validate="isPasswd" name="passwd" value="">
                    </div>
                  </div>

                  <div class=" form-group">
                    <label class="form-control-label">{l s='Confirmation' d='Shop.Forms.Labels'}</label>
                    <div class="">
                      <input class="form-control" type="password" data-validate="isPasswd" name="confirmation" value="">
                    </div>
                  </div>

                  <input type="hidden" name="token" id="token" value="{$customer_token}">
                  <input type="hidden" name="id_customer" id="id_customer" value="{$id_customer}">
                  <input type="hidden" name="reset_token" id="reset_token" value="{$reset_token}">

                  <div class=" form-group">
                    <div>
                      <button class="btn btn-primary" type="submit" name="submit">
                        {l s='Change Password' d='Shop.Theme.Actions'}
                      </button>
                    </div>
                  </div>
                </div>

              </section>
            </form>
        </div>
    </div>
  </div>
{/block}

