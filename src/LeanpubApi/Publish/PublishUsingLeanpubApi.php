<?php
declare(strict_types=1);

namespace LeanpubApi\Publish;

use Assert\Assert;
use LeanpubApi\Common\LeanpubApiClient;

final class PublishUsingLeanpubApi implements Publish
{
    private LeanpubApiClient $leanpubApiClient;

    public function __construct(LeanpubApiClient $leanpubApiClient)
    {
        $this->leanpubApiClient = $leanpubApiClient;
    }

    public function publishNewVersion(): void
    {
        $decodedData = $this->leanpubApiClient->postFormData('/publish.json', []);

        if (!isset($decodedData['success'])) {
            throw CouldNotPublishNewVersion::unknownReason($decodedData);
        }
    }

    public function publishNewVersionAndEmailReaders(string $emailMessage): void
    {
        Assert::that(trim($emailMessage))
            ->notEmpty('When emailing your readers the email message should not be empty');

        $decodedData = $this->leanpubApiClient->postFormData(
            '/publish.json',
            [
                'publish[email_readers]' => 'true',
                'publish[release_notes]' => $emailMessage
            ]
        );

        if (!isset($decodedData['success'])) {
            throw CouldNotPublishNewVersion::unknownReason($decodedData);
        }
    }
}
