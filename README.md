HautziSystemMailBundle
======================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7c3d1f06-d02e-44cf-ac24-3cad04ac8ebf/mini.png)](https://insight.sensiolabs.com/projects/7c3d1f06-d02e-44cf-ac24-3cad04ac8ebf)

This bundle abstracts away sending system Messages. If instanciating a `Swift_Message` instance in your controllers
seems fishy to you, this is probably what you are looking for.

This idea of this Bundle is to create some sort of XML configuration for each system mail to be sent (like "you have 
registered successfully, please activate your account" or "hey dude, you haven't been wasted your money on our platform
for 10 days, please come back"), leveraging the power of twig.

Hey, why not looking in the code? It's kind of self-explanatory:

```twig
// AppBundle/Resources/emails/send-info.xml.twig
<email>
    <to name="{{ user.name }}">{{ user.email }}</to>
    <subject locale="de">Hallo {{ user.name }}</subject>
    <subject locale="en">Hello {{ user.name }}</subject>
    <messageHtml locale="en"><![CDATA[
        Hallo {{ user.name }}, 
        <a href="{{ url('') }}">please click this link</a>.
    ]]></messageHtml>
    <messageText locale="en" removeIndent="true">
        Hello {{ user.name }}, 
        please click this link: {{ url('') }}
    </messageText>
</email>
```
Then send out this mail explicitly by simply calling: 

```php
// from your code simply call
$container->get('system_mailer')->send('App:send-info', [
    'user' => $user,
]);
```

Installation
------------

### Step 1: Download the Bundle ###

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require hautzi/system-mail-bundle "~0.1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle ###

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Hautzi\SystemMailBundle\HautziSystemMailBundle(),
        );

        // ...
    }

    // ...
}
```

Configuration Options
---------------------

Right now, you can provide default data

```yaml
# in your app/config.yml
hautzi_system_mail:
    # defaults that are set for each mail, use blank 
    # if you don't want to set this
    defaults:
        subject:   ~
        replyTo:   ~
        from:
            email: ~
            name:  ~
        to:
            email: ~
            name:  ~
        cc:
            email: ~
            name:  ~
        bcc:
            email: ~
            name:  ~
```



Examples
--------

```php
$systemMailer = $container->get('system_mailer');

// sends out AppBundle/Resources/emails/registration/confirmUser.xml.twig
$systemMailer->send('App:registration/confirmUser', ['user' => $user]);

// force locale of sent mail (when the recipient speaks another language than the user in the session)
$systemMailer->send('App:info-mail', ['user' => $user], 'de');

// attach file to mail (or do something else with the Swift_Message instance)
$systemMailer->send('App:message-with-pdf', [], null, function (\Swift_Message $message) {
     $message->attach(\Swift_Attachment::fromPath('my-document.pdf'))
});
```


License
-------

This bundle is under the MIT license. See the complete license in the `LICENSE` file in the root dir of the bundle.
