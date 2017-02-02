<?php
namespace CMS\Bundle\CoreBundle\Manager;

use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;
use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\ContactBundle\Entity\Message;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Gestion des emails aux utilisateurs
 *
 * @package scms
 * @subpackage CoreBundle
 * @since 0.1
 */
class MailerManager
{

    protected $mailer;
    protected $templating;
    protected $em;
    protected $translator;

    private $prefixTemplate     = 'CoreBundle:Mail:';
    private $suffixTemplateTxt  = '.txt.twig';
    private $suffixTemplateHtml = '.html.twig';
    private $from               = 'leoncorono@gmail.com';

    /**
     * Crée l'objet MailerManager
     * @param \Swift_Mailer   $mailer     Objet qui permet d'envoyer le mail
     * @param EngineInterface $templating Gestion des template
     * @param EntityManager   $em         Gestion des entités
     * @param Translator      $translator Gestion de la traduction
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em, TranslatorInterface $translator)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->em         = $em;
        $this->translator = $translator;
    }

    /**
     * Envoie le message de confirmation de création de l'utilisateur
     * Ce message contient un lien qui permet d'activer l'utilisateur
     *
     * @param  User   $user L'utilisateur à confirmer
     * @return boolean
     */
    public function sendConfirmationUser(User $user, $pass_clear, $site_name)
    {
    	$template = $this->prefixTemplate.'registration';
    	$body     = $this->templating->render($template.$this->suffixTemplateTxt, array('user' => $user, 'password_clear' => $pass_clear, 'site_name' => $site_name));
    	$bodyHtml = $this->templating->render($template.$this->suffixTemplateHtml, array('user' => $user, 'password_clear' => $pass_clear, 'site_name' => $site_name));
    	$from     = $this->from;
    	$to       = $user->getUserEmail();
    	$subject  = $this->translator->trans('cms.user.confirmation.subject');

    	return $this->_sendMessage($from, $to, $subject, $body, $bodyHtml);
    }

    public function sendForgotPassword(User $user)
    {
        $template = $this->prefixTemplate.'forgotpassword';
        $body     = $this->templating->render($template.$this->suffixTemplateTxt, array('user' => $user));
        $bodyHtml = $this->templating->render($template.$this->suffixTemplateHtml, array('user' => $user));
        $from     = $this->from;
        $to       = $user->getUserEmail();
        $subject  = $this->translator->trans('cms.user.forgotpassword.subject');

        return $this->_sendMessage($from, $to, $subject, $body, $bodyHtml);
    }

    /**
     * Envoie le mail, récupère les destinataires qui ne sont pas valides
     *
     * @param  String $from       addresse email d'envoi
     * @param  mixed  $to         adresse(s) email(s) de réception
     * @param  String $subject    Sujet de l'email
     * @param  String $body       Template au format txt de l'email
     * @param  String $bodyHtml   Template au format html de l'email
     * @param  mixed  $attachment Piece jointe
     * @return mixed              Vrai si le message a été envoyé et un tableau d'adresses email sinon
     */
    private function _sendMessage($from, $to, $subject, $body, $bodyHtml, $attachment=null)
    {
        $mail = \Swift_Message::newInstance();

        $mail->setFrom($from)
             ->setTo($to)
             ->setSubject($subject)
             ->setBody($body)
             ->addPart($bodyHtml,'text/html');

        if (!is_null($attachment)) {
           $mail->attach($attachment);
        }

        $failedRecipients = array();

        $status = $this->mailer->send($mail, $failedRecipients);
        $message = new Message();
        $message->setSender($from);
        if (!is_array($to)) {
            $to = array($to);
        }
        $message->setReceivers($to);
        $message->setMessage($bodyHtml);
        $message->setSubject($subject);
        $message->setSentDate(new \DateTime());
        $message->setStatus(2);
        $this->em->persist($message);
        $this->em->flush();
        if ($status && empty($failedRecipients)) {
            return true;
        }

        return $failedRecipients;
    }

    /**
     * Envoie le mail, récupère les destinataires qui ne sont pas valides
     * @param  Message $message message à envoyer
     * @return mixed            Vrai si le message a été envoyé et un tableau d'adresses email sinon
     */
    public function sendMessage(Message $message, $attachment = null)
    {
        $mail = \Swift_Message::newInstance();

        $template = $this->prefixTemplate.'mailadmin';
        $body     = $this->templating->render($template.$this->suffixTemplateTxt, array('message' => $message));
        $bodyHtml = $this->templating->render($template.$this->suffixTemplateHtml, array('message' => $message));

        $mail->setFrom($message->getSender())
             ->setTo($message->getReceivers())
             ->setSubject($message->getSubject())
             ->setBody($body)
             ->addPart($bodyHtml,'text/html');

        if (!is_null($attachment)) {
           $mail->attach($attachment);
        }

        $failedRecipients = array();

        $status = $this->mailer->send($mail, $failedRecipients);

        $message->setStatus(2);
        $this->em->persist($message);
        $this->em->flush();
        if ($status && empty($failedRecipients)) {
            return true;
        }

        return $failedRecipients;
    }

}