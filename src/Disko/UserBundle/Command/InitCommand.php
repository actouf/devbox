<?php
namespace Disko\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\DateTime;
use Disko\UserBundle\Entity\User;
use Disko\UserBundle\Entity\Localisation;

class InitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('disko:init:start')
            ->setDescription('Init fixture start application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $this->addUser(array(
            'username' => 'admin',
            'firstname'=> 'admin',
            'lastname' => 'admin'.'ing',
            'email'    => 'admin@admin.com',
            'password' => 'admin',
            'role'     => 'ROLE_SUPER_ADMIN',
            'enabled'  => true
        ));
    }


    /**
     * Add user on base
     *
     * @param $settings
     */
    protected function addUser($settings)
    {
        $userRepository = $this->em->getRepository('UserBundle:User');
        if (!$userRepository->findOneBy(array('email' => $settings['email'])))
        {
            $userManager = $this->getContainer()->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setUsername($settings['username']);
            $user->setEmail($settings['email']);
            $user->setFirstName($settings['firstname']);
            $user->setLastName($settings['lastname']);
            $user->setPlainPassword($settings['password']);
            $user->setEnabled($settings['enabled']);
            $user->setBirthday(new \DateTime());

            if (isset($settings['role'])) {
                $user->addRole($settings['role']);
            }

            $userManager->updateUser($user, true);

            $localisation = new Localisation();
            $localisation->setTitle('Facturation');
            $localisation->setAddress('11 rue de la ginette');
            $localisation->setAddressMore('');
            $localisation->setPhone('04 75 53 07 39');
            $localisation->setCode('25000');
            $localisation->setCity('Bouligne');
            $localisation->setCountry('FR');
            $user->addLocalisation($localisation);

            $userManager->updateUser($user, true);
        }
    }

}