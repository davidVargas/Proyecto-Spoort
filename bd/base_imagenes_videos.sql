-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`contacto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`contacto` (
  `id_contacto` INT(11) NOT NULL COMMENT '',
  `email` VARCHAR(45) NOT NULL COMMENT '',
  `telefono` VARCHAR(45) NOT NULL COMMENT '',
  `nombre_usuario` VARCHAR(50) NOT NULL COMMENT '',
  `apellido_usuario` VARCHAR(50) NOT NULL COMMENT '',
  PRIMARY KEY (`id_contacto`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = ascii;

CREATE UNIQUE INDEX `email` ON `mydb`.`contacto` (`email` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `mydb`.`deportes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`deportes` (
  `id_deportes` INT(11) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id_deportes`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = ascii;


-- -----------------------------------------------------
-- Table `mydb`.`provincia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`provincia` (
  `id_provincia` INT(11) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id_provincia`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = ascii;


-- -----------------------------------------------------
-- Table `mydb`.`tipo_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tipo_usuario` (
  `id_tipo_usuario` INT(11) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id_tipo_usuario`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = ascii;


-- -----------------------------------------------------
-- Table `mydb`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`usuario` (
  `id_usuario` INT(11) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  `contra` VARCHAR(45) NOT NULL COMMENT '',
  `fk_contacto` INT(11) NOT NULL COMMENT '',
  `fk_tipoUsuario` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id_usuario`, `fk_contacto`, `fk_tipoUsuario`)  COMMENT '',
  CONSTRAINT `fk_usuario_contacto1`
    FOREIGN KEY (`fk_contacto`)
    REFERENCES `mydb`.`contacto` (`id_contacto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_tipo_usuario1`
    FOREIGN KEY (`fk_tipoUsuario`)
    REFERENCES `mydb`.`tipo_usuario` (`id_tipo_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = ascii;

CREATE UNIQUE INDEX `nombre_UNIQUE` ON `mydb`.`usuario` (`nombre` ASC)  COMMENT '';

CREATE INDEX `fk_usuario_contacto1_idx` ON `mydb`.`usuario` (`fk_contacto` ASC)  COMMENT '';

CREATE INDEX `fk_usuario_tipo_usuario1_idx` ON `mydb`.`usuario` (`fk_tipoUsuario` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `mydb`.`equipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`equipo` (
  `id_equipo` INT(11) NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NOT NULL COMMENT '',
  `descripcion` VARCHAR(45) NOT NULL COMMENT '',
  `id_usuario` INT(11) NOT NULL COMMENT '',
  `direccion` VARCHAR(545) NOT NULL COMMENT '',
  `id_provincia` INT(11) NOT NULL COMMENT '',
  `id_deportes` INT(11) NOT NULL COMMENT '',
  `horario` VARCHAR(245) NOT NULL COMMENT '',
  `genero` VARCHAR(10) NOT NULL COMMENT '',
  PRIMARY KEY (`id_equipo`)  COMMENT '',
  CONSTRAINT `fk_equipo_provincia1`
    FOREIGN KEY (`id_provincia`)
    REFERENCES `mydb`.`provincia` (`id_provincia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_equipo_usuario1`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `mydb`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_equipo_deportes1`
    FOREIGN KEY (`id_deportes`)
    REFERENCES `mydb`.`deportes` (`id_deportes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = ascii;

CREATE INDEX `fk_equipo_provincia1_idx` ON `mydb`.`equipo` (`id_provincia` ASC)  COMMENT '';

CREATE INDEX `fk_equipo_usuario1_idx` ON `mydb`.`equipo` (`id_usuario` ASC)  COMMENT '';

CREATE INDEX `fk_equipo_deportes1_idx` ON `mydb`.`equipo` (`id_deportes` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `mydb`.`imagen_perfil`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`imagen_perfil` (
  `id_imagen` INT(11) NOT NULL COMMENT '',
  `url` VARCHAR(1000) NOT NULL COMMENT '',
  `nombre` VARCHAR(40) NOT NULL COMMENT '',
  `extension` VARCHAR(7) NOT NULL COMMENT '',
  `id_equipo` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id_imagen`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = ascii;


-- -----------------------------------------------------
-- Table `mydb`.`video`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`video` (
  `id_video` INT(11) NOT NULL COMMENT '',
  `url` VARCHAR(200) NOT NULL COMMENT '',
  `nombre_video` VARCHAR(145) NOT NULL COMMENT '',
  `id_equipo` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id_video`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = ascii;


-- -----------------------------------------------------
-- Table `mydb`.`imagenes_equipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`imagenes_equipo` (
  `id_imagenes_equipo` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `url` VARCHAR(1000) NOT NULL COMMENT '',
  `nombre_imagen` VARCHAR(145) NOT NULL COMMENT '',
  `extension_imagen` VARCHAR(10) NOT NULL COMMENT '',
  `id_equipo` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id_imagenes_equipo`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_imagenes_equipo_UNIQUE` ON `mydb`.`imagenes_equipo` (`id_imagenes_equipo` ASC)  COMMENT '';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
