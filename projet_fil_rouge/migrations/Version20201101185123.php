<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101185123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_BFDD3168A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_category (articles_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A7D8EFDB1EBAF6CC (articles_id), INDEX IDX_A7D8EFDB12469DE2 (category_id), PRIMARY KEY(articles_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, articles_id INT DEFAULT NULL, evenements_id INT DEFAULT NULL, informations_id INT DEFAULT NULL, photos_id INT DEFAULT NULL, videos_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX IDX_D9BEC0C4A76ED395 (user_id), INDEX IDX_D9BEC0C41EBAF6CC (articles_id), INDEX IDX_D9BEC0C463C02CD4 (evenements_id), INDEX IDX_D9BEC0C490587D82 (informations_id), INDEX IDX_D9BEC0C4301EC62 (photos_id), INDEX IDX_D9BEC0C4763C10B2 (videos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenements (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, INDEX IDX_E10AD400A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenements_category (evenements_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_D3B3B32E63C02CD4 (evenements_id), INDEX IDX_D3B3B32E12469DE2 (category_id), PRIMARY KEY(evenements_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informations (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX IDX_6F966489A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informations_category (informations_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C361BA7D90587D82 (informations_id), INDEX IDX_C361BA7D12469DE2 (category_id), PRIMARY KEY(informations_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_876E0D9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos_category (photos_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7013E9DE301EC62 (photos_id), INDEX IDX_7013E9DE12469DE2 (category_id), PRIMARY KEY(photos_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, published TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_29AA6432A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos_category (videos_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_BF92C26A763C10B2 (videos_id), INDEX IDX_BF92C26A12469DE2 (category_id), PRIMARY KEY(videos_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE articles_category ADD CONSTRAINT FK_A7D8EFDB1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_category ADD CONSTRAINT FK_A7D8EFDB12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C463C02CD4 FOREIGN KEY (evenements_id) REFERENCES evenements (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C490587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id)');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evenements_category ADD CONSTRAINT FK_D3B3B32E63C02CD4 FOREIGN KEY (evenements_id) REFERENCES evenements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenements_category ADD CONSTRAINT FK_D3B3B32E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D90587D82 FOREIGN KEY (informations_id) REFERENCES informations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE informations_category ADD CONSTRAINT FK_C361BA7D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos_category ADD CONSTRAINT FK_7013E9DE301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_category ADD CONSTRAINT FK_7013E9DE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA6432A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE videos_category ADD CONSTRAINT FK_BF92C26A763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videos_category ADD CONSTRAINT FK_BF92C26A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles_category DROP FOREIGN KEY FK_A7D8EFDB1EBAF6CC');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C41EBAF6CC');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C463C02CD4');
        $this->addSql('ALTER TABLE evenements_category DROP FOREIGN KEY FK_D3B3B32E63C02CD4');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C490587D82');
        $this->addSql('ALTER TABLE informations_category DROP FOREIGN KEY FK_C361BA7D90587D82');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4301EC62');
        $this->addSql('ALTER TABLE photos_category DROP FOREIGN KEY FK_7013E9DE301EC62');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4763C10B2');
        $this->addSql('ALTER TABLE videos_category DROP FOREIGN KEY FK_BF92C26A763C10B2');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE articles_category');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE evenements_category');
        $this->addSql('DROP TABLE informations');
        $this->addSql('DROP TABLE informations_category');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE photos_category');
        $this->addSql('DROP TABLE videos');
        $this->addSql('DROP TABLE videos_category');
    }
}
