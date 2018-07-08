<?php

namespace AppBundle\Security;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CategoryVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Category) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Category $category */
        $category = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($category, $user);
            case self::EDIT:
                return $this->canEdit($category, $user);
            case self::DELETE:
                return $this->canDelete($category, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Category $category, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($category, $user)) {
            return true;
        }

        if($user->hasRole("ROLE_SUPER_ADMIN")){
            return true;
        }

        return false;
    }

    private function canEdit(Category $category, User $user)
    {
        return $user === $category->getUser() || $user->hasRole("ROLE_SUPER_ADMIN");
    }

    private function canDelete(Category $category, User $user)
    {
        return $this->canEdit($category, $user);
    }
}