<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704205500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_of_borrowing to book table.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('book')) {
            $table = $schema->getTable('book');
            if (!$table->hasColumn('date_of_borrow')) {
                $this->addSql('ALTER TABLE book ADD date_of_borrowing TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('book')) {
            $table = $schema->getTable('book');
            if ($table->hasColumn('date_of_borrow')) {
                $this->addSql('ALTER TABLE book DROP date_of_borrowing');
            }
        }
    }
}
