SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `pete` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `pete` ;

-- -----------------------------------------------------
-- Table `pete`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pete`.`user` ;

CREATE  TABLE IF NOT EXISTS `pete`.`user` (
  `userid` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `mail` VARCHAR(60) NOT NULL ,
  `password` VARCHAR(60) NOT NULL ,
  `activ` TINYINT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`userid`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `mail_UNIQUE` (`mail` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pete`.`texty`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pete`.`texty` ;

CREATE  TABLE IF NOT EXISTS `pete`.`texty` (
  `textyid` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(45) NOT NULL ,
  `Texty` TEXT NOT NULL ,
  `klicks` BIGINT NOT NULL DEFAULT 0 ,
  `votepoints` BIGINT NOT NULL DEFAULT 0 ,
  `activ` TINYINT NOT NULL DEFAULT 1 ,
  `userfs` INT NOT NULL ,
  PRIMARY KEY (`textyid`) ,
  INDEX `fk_texty_user_idx` (`userfs` ASC) ,
  CONSTRAINT `fk_texty_user`
    FOREIGN KEY (`userfs` )
    REFERENCES `pete`.`user` (`userid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pete`.`abonnement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pete`.`abonnement` ;

CREATE  TABLE IF NOT EXISTS `pete`.`abonnement` (
  `aboID` INT NOT NULL AUTO_INCREMENT ,
  `userfs` INT NOT NULL ,
  `mail` VARCHAR(70) NULL ,
  `activationkey` VARCHAR(70) NOT NULL ,
  `activ` TINYINT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`aboID`) ,
  INDEX `fk_abonnement_user1_idx` (`userfs` ASC) ,
  UNIQUE INDEX `activationkey_UNIQUE` (`activationkey` ASC) ,
  CONSTRAINT `fk_abonnement_user1`
    FOREIGN KEY (`userfs` )
    REFERENCES `pete`.`user` (`userid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pete`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pete`.`comment` ;

CREATE  TABLE IF NOT EXISTS `pete`.`comment` (
  `commentid` INT NOT NULL AUTO_INCREMENT ,
  `userfs` INT NOT NULL ,
  `textyfs` INT NOT NULL ,
  `comment` TEXT NOT NULL ,
  `activ` TINYINT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`commentid`) ,
  INDEX `fk_comment_user1_idx` (`userfs` ASC) ,
  INDEX `fk_comment_texty1_idx` (`textyfs` ASC) ,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`userfs` )
    REFERENCES `pete`.`user` (`userid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_texty1`
    FOREIGN KEY (`textyfs` )
    REFERENCES `pete`.`texty` (`textyid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pete`.`session`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pete`.`session` ;

CREATE  TABLE IF NOT EXISTS `pete`.`session` (
  `sessionid` INT NOT NULL AUTO_INCREMENT ,
  `userfs` INT NOT NULL ,
  `sessionkey` VARCHAR(60) NOT NULL ,
  `browserkey` VARCHAR(60) NOT NULL ,
  `lastactiv` VARCHAR(60) NOT NULL ,
  `activ` TINYINT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`sessionid`) ,
  INDEX `fk_session_user1_idx` (`userfs` ASC) ,
  UNIQUE INDEX `sessionkey_UNIQUE` (`sessionkey` ASC) ,
  CONSTRAINT `fk_session_user1`
    FOREIGN KEY (`userfs` )
    REFERENCES `pete`.`user` (`userid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `pete` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
