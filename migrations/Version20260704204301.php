<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704204301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add library_member_id to book table (a book can be borrowed by one library member).';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('library_member') && $schema->hasTable('book')) {
            $this->addSql('ALTER TABLE book ADD library_member_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3316F6BA472 FOREIGN KEY (library_member_id) REFERENCES library_member (id) ON DELETE SET NULL NOT DEFERRABLE');
            $this->addSql('CREATE INDEX IDX_CBE5A3316F6BA472 ON book (library_member_id)');
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('library_member') && $schema->hasTable('book')) {
            $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A3316F6BA472');
            $this->addSql('DROP INDEX IDX_CBE5A3316F6BA472');
            $this->addSql('ALTER TABLE book DROP library_member_id');
        }
    }
}
