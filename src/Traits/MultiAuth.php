<?php

namespace Marqant\AuthGraphQL\Traits;


use Illuminate\Database\Eloquent\Relations\MorphOne;
use Marqant\AuthGraphQL\Models\AuthCriteria;


/**
 * Trait MultiAuth
 *
 * This is to make any model have authentication criteria.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait MultiAuth
{
    /**
     * Relation from the Model to the AuthCriteria
     *
     * @return MorphOne
     */
    public function authCriteria(): MorphOne
    {
        return $this->morphOne(AuthCriteria::class, 'authcriteria');
    }

    /**
     * Set the auth criteria. This could be a random unique token, or an email address, or up to you.
     *
     * @param string $criteria
     * @return
     */
    public function setCriteria(string $criteria): AuthCriteria
    {
        $AuthCriteria = AuthCriteria::create([
            'id' => $criteria,
        ]);

        $this->authCriteria()->save($AuthCriteria);

        return $AuthCriteria;
    }

    /**
     * Set credentials for existing Auth entry.
     *
     * @param string $credentials
     */
    public function setCredentials(string $credentials)
    {
        $AuthCriteria = $this->authCriteria;

        $AuthCriteria->credentials = $credentials;

        $AuthCriteria->save();
    }

    /**
     * Check the provided credentials against the model's credentials.
     *
     * @param $provided_credentials
     * @return bool
     */
    public function checkCredentials($provided_credentials): bool
    {
        $AuthCriteria = $this->authcriteria;

        if (!$AuthCriteria) {
            return false;
        }

        return $this->authcriteria['credentials'] === $provided_credentials;
    }
}
