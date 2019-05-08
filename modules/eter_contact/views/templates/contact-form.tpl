<div class="modal fade contact-server-modal" id="contact-server-modal" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="close"><i class="material-icons">close</i></div>
            <div class="form-contact">
                <img src="{$image}" class="contact-bg-image" alt="contact us">
                <div class="contact-thanks hidden">
                    <h2>{l s='Thanks' mod='eter_contact'}</h2>
                    <p class="message">{l s='For contact us' mod='eter_contact'}</p>
                    <img src="{$thanks}" class="spinner" alt="thanks">
                    <p class="sub-message">{l s='Your message will be reviewed as soon as posible and we will send you an answer to your email' mod='eter_contact'}</p>
                </div>
                <div class="contact-loader hidden">
                    <p class="message">{l s='We are processing your request' mod='eter_contact'}</p>
                    <img src="{$spinner}" class="spinner" alt="contact us">
                </div>
                <form class="contact-form needs-validation" method="post" role="form">
                    <p class="mail-image">
                        <img src="{$mail}" alt="{l s='Email' mod='eter_contact'}">
                    </p>
                    <div class="inputs-group">
                        <p class="title">{l s='Contact' mod='eter_contact'}</p>
                        <p class="contact-post-message hidden"></p>
                        <div class="controls">
                            <input type="hidden" name="contact-form" value="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><span class="red-ast">*</span>{l s='Name' mod='eter_contact'}</label>
                                        <input type="text" name="name" class="form-control"  required="required" data-error="Firstname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><span class="red-ast">*</span>{l s='Email' mod='eter_contact'}</label>
                                        <input type="email" name="email" class="form-control"  required="required" data-error="Valid email is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><span class="red-ast">*</span>{l s='Message' mod='eter_contact'}</label>
                                        <textarea name="message" class="form-control"  rows="4" required="required" data-error="Please,leave us a message."></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn send-message">
                            {l s='Contact' mod='eter_popup'}
                        </button>
                    </div>
                </form>
            </div>
            <div class="information">
                <div class="email">
                    <i class="material-icons">email</i>
                    <span>{$email}</span>
                </div>
                <div class="phone">
                    <i class="material-icons">phone</i>
                    <span>{$phone}</span>
                </div>
                <div class="address">
                    <i class="material-icons">location_on</i>
                    <span>
                        {$address}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
