<?php 
    error_reporting(E_ALL);
    ini_set("display_errors", 1);


    abstract class Request {
        private PDO $pdo;
        protected PDOStatement $request;

        private string $__DB_NAME = "group05";
        private string $__DB_HOST = "db";
        private string $__DB_USERNAME = "group05";
        private string $__DB_PASSWORD = "group05";
        

        public function __construct() {
            $this->pdo = new PDO("mysql:dbname=$this->__DB_NAME;host=$this->__DB_HOST", $this->__DB_USERNAME, $this->__DB_PASSWORD, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
            ]);
        }

        protected function buildRequest(string $sql): void {
            $this->request = $this->pdo->prepare($sql);
        }

        protected function getLastID(): int {
            return $this->pdo->lastInsertId();
        }
    }

    class Agence extends Request {
        private int $ID;
        private string $NOM;
        private string $URL;
        private string $FUSEAU_HORAIRE;
        private string $TELEPHONE;
        private string $SIEGE;

        public function __construct() {
            parent::__construct();
        }

        public static function withData(string $nom, string $url, string $fuseau, string $tel, string $siege): self {
            $instance = new self();
            $instance->setNom($nom);
            $instance->setURL($url);
            $instance->setFuseau($fuseau);
            $instance->setTelephone($tel);
            $instance->setSiege($siege);
            return $instance;
        }

        public function getID(): int { return $this->ID; }
        public function getNom(): string { return $this->NOM; }
        public function getURL(): string { return $this->URL; }
        public function getFuseau(): string { return $this->FUSEAU_HORAIRE; }
        public function getTelephone(): string { return $this->TELEPHONE; }
        public function getSiege(): string { return $this->SIEGE; }

        public function setNom(string $nom): void { $this->NOM = $nom; }
        public function setURL(string $url): void { $this->URL = $url; }
        public function setFuseau(string $fuseau): void { $this->FUSEAU_HORAIRE = $fuseau; }
        public function setTelephone(string $tel): void { $this->TELEPHONE = $tel; }
        public function setSiege(string $siege): void { $this->SIEGE = $siege; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM AGENCE");
            $this->request->execute([]);

            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(int $id): ?Agence {
            $this->buildRequest("SELECT * FROM AGENCE WHERE ID = ?");
            $this->request->execute([$id]);

            return $this->request->fetchObject(self::class);
        }

        public function search(array $filters): array {
            $query = "SELECT * FROM AGENCE WHERE 1=1";
        
            foreach ($filters as $key => $value) {
                if (!empty($value))
                    $query .= " AND $key LIKE CONCAT('%', :$key, '%')";
            }
        
            $this->buildRequest($query);
            $this->request->execute($filters);
        
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    }

    class Horraire extends Request {
        private string $TRAJET_ID;
        private int $ITINERAIRE_ID;
        private int $ARRET_ID;
        private ?string $HEURE_ARRIVEE;
        private ?string $HEURE_DEPART;
    
        public function __construct() {
            parent::__construct();
        }
    
        public static function withData(
            string $trajetId,
            int $itineraireId,
            int $arretId,
            ?string $heureArrivee = null,
            ?string $heureDepart = null
        ): self {
            $instance = new self();
            $instance->setTrajetId($trajetId);
            $instance->setItineraireId($itineraireId);
            $instance->setArretId($arretId);
            $instance->setHeureArrivee($heureArrivee);
            $instance->setHeureDepart($heureDepart);
            return $instance;
        }
    
        public function getTrajetId(): string { return $this->TRAJET_ID; }
        public function getItineraireId(): int { return $this->ITINERAIRE_ID; }
        public function getArretId(): int { return $this->ARRET_ID; }
        public function getHeureArrivee(): ?string { return $this->HEURE_ARRIVEE; }
        public function getHeureDepart(): ?string { return $this->HEURE_DEPART; }
    
        public function setTrajetId(string $trajetId): void { $this->TRAJET_ID = $trajetId; }
        public function setItineraireId(int $itineraireId): void { $this->ITINERAIRE_ID = $itineraireId; }
        public function setArretId(int $arretId): void { $this->ARRET_ID = $arretId; }
        public function setHeureArrivee(?string $heureArrivee): void { $this->HEURE_ARRIVEE = $heureArrivee; }
        public function setHeureDepart(?string $heureDepart): void { $this->HEURE_DEPART = $heureDepart; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM HORRAIRE");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(string $trajetId, int $arretId): ?Horraire {
            $this->buildRequest("SELECT * FROM HORRAIRE WHERE TRAJET_ID = ? AND ARRET_ID = ?");
            $this->request->execute([$trajetId, $arretId]);
    
            return $this->request->fetchObject(self::class);
        }

        public function create(): int {
            $this->buildRequest("INSERT INTO HORRAIRE (TRAJET_ID, ITINERAIRE_ID, ARRET_ID, HEURE_ARRIVEE, HEURE_DEPART) VALUES (?, ?, ?, ?, ?)");
            $this->request->execute([
                $this->getTrajetId(),
                $this->getItineraireId(),
                $this->getArretId(),
                $this->getHeureArrivee(),
                $this->getHeureDepart()
            ]);

            return $this->getLastID();
        }

        public function search(array $filters): array {
            $query = "SELECT * FROM HORRAIRE WHERE 1=1";
        
            foreach ($filters as $key => $value) {
                if ($key === 'HEURE_ARRIVEE' || $key === 'HEURE_DEPART') {
                    if (!empty($value)) {
                        $query .= " AND $key = :$key";
                    }
                } 
                elseif (!empty($value)) {
                    $query .= " AND $key LIKE CONCAT('%', :$key, '%')";
                }
            }
        
            $this->buildRequest($query);
            $this->request->execute($filters);
        
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    }

    class ExceptionService extends Request {
        private int $SERVICE_ID;
        private string $DATE;
        private int $CODE;

        public function __construct() {
            parent::__construct();
        }

        public static function withData(int $serviceId, string $date, int $code): self {
            $instance = new self();
            $instance->setServiceId($serviceId);
            $instance->setDate($date);
            $instance->setServiceCode($code);
            return $instance;
        }

        public function getServiceId(): int { return $this->SERVICE_ID; }
        public function getDate(): string { return $this->DATE; }
        public function getserviceCode(): int { return $this->CODE; }

        public function setServiceId(int $serviceId): void { $this->SERVICE_ID = $serviceId; }
        public function setDate(string $date): void { $this->DATE = $date; }
        public function setServiceCode(int $code): void { $this->CODE = $code; }

        public function findAll(): array {
            $this->buildRequest("SELECT * FROM EXCEPTION");
            $this->request->execute([]);

            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }

        public function findById(int $id): ?ExceptionService {
            $this->buildRequest("SELECT * FROM EXCEPTION WHERE ID = ?");
            $this->request->execute([$id]);

            return $this->request->fetchObject(self::class);
        }

        public function create(): void {
            $this->buildRequest("INSERT INTO EXCEPTION (SERVICE_ID, DATE, CODE) VALUES (?, ?, ?)");
            $this->request->execute([
                $this->getServiceId(),
                $this->getDate(),
                $this->getServiceCode()
            ]);
        }

        public function search(array $filters): array {
            $query = "SELECT * FROM EXCEPTION WHERE 1=1";
        
            foreach ($filters as $key => $value) {
                if ($key === 'DATE') {
                    if (!empty($value)) {
                        $query .= " AND $key = :$key";
                    }
                } 
                elseif (!empty($value)) {
                    $query .= " AND $key LIKE CONCAT('%', :$key, '%')";
                }
            }
        
            $this->buildRequest($query);
            $this->request->execute($filters);
        
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    }

    class Arret extends Request {
        private int $ID;
        private string $NOM;
        private string $LATITUDE;
        private string $LONGITUDE;
    
        public function __construct() {
            parent::__construct();
        }

        public static function withData(string $nom, string $latitude, string $longitude): self {
            $instance = new self();
            $instance->setNom($nom);
            $instance->setLatitude($latitude);
            $instance->setLongitude($longitude);
            return $instance;
        }
    
        public function getID(): int { return $this->ID; }
        public function getNom(): string { return $this->NOM; }
        public function getLatitude(): string { return $this->LATITUDE; }
        public function getLongitude(): string { return $this->LONGITUDE; }

        public function setNom(string $nom): void { $this->NOM = $nom; }
        public function setLatitude(string $latitude): void { $this->LATITUDE = $latitude; }
        public function setLongitude(string $longitude): void { $this->LONGITUDE = $longitude; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM ARRET");
            $this->request->execute([]);

            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(int $id): ?Arret {
            $this->buildRequest("SELECT * FROM ARRET WHERE ID = ?");
            $this->request->execute([$id]);

            return $this->request->fetchObject(self::class);
        }

        public function search(string $nom = '', int $numero = 0) {
            $this->buildRequest("SELECT * FROM ARRET_TRAINS_PAR_SERVICE WHERE ARRET_NOM LIKE CONCAT('%', ?, '%') AND (TOTAL_ARRIVEES >= ? OR TOTAL_DEPARTS >= ?)");
            $this->request->execute([$nom, $numero, $numero]);

            return $this->request->fetchAll(PDO::FETCH_CLASS, get_class(new class {
                private int $ARRET_ID;
                private string $ARRET_NOM;
                private int|null $SERVICE_ID;
                private int $TOTAL_ARRIVEES;
                private int $TOTAL_DEPARTS;
            
                public function getArretId(): int { return $this->ARRET_ID; }
                public function getArretNom(): string { return $this->ARRET_NOM; }
                public function getServiceId(): int { return $this->SERVICE_ID; }
                public function getTotalArrivees(): int { return $this->TOTAL_ARRIVEES; }
                public function getTotalDeparts(): int { return $this->TOTAL_DEPARTS; }
            }));
        }

        public function create(): int {
            $this->buildRequest("INSERT INTO ARRET (NOM, LATITUDE, LONGITUDE) VALUES (?, ?, ?)");
            $this->request->execute([
                $this->getNom(),
                $this->getLatitude(),
                $this->getLongitude()
            ]);

            return $this->getLastID();
        }

        public function update(): void {
            $latMin = 49.5294835476;
            $latMax = 51.4750237087;
            $lonMin = 2.51357303225;
            $lonMax = 6.15665815596;
        
            $latitude = $this->getLatitude();
            $longitude = $this->getLongitude();
        
            if ($latitude < $latMin || $latitude > $latMax || $longitude < $lonMin || $longitude > $lonMax) {
                throw new InvalidArgumentException("Les coordonnées doivent être situées en Belgique.");
            }
        
            $this->buildRequest("UPDATE ARRET SET NOM = ?, LATITUDE = ?, LONGITUDE = ? WHERE ID = ?");
            $this->request->execute([
                $this->getNom(),
                $latitude,
                $longitude,
                $this->getID()
            ]);
        }
    }

    class Service extends Request {
        private int $ID;
        private string $NOM;
        private bool $LUNDI;
        private bool $MARDI;
        private bool $MERCREDI;
        private bool $JEUDI;
        private bool $VENDREDI;
        private bool $SAMEDI;
        private bool $DIMANCHE;
        private string $DATE_DEBUT;
        private string $DATE_FIN;
    
        public function __construct() {
            parent::__construct();
        }

        public static function withData(
            string $nom,
            bool $lundi,
            bool $mardi,
            bool $mercredi,
            bool $jeudi,
            bool $vendredi,
            bool $samedi,
            bool $dimanche,
            string $dateDebut,
            string $dateFin
        ): self {
            $instance = new self();
            $instance->setNom($nom);
            $instance->setLundi($lundi);
            $instance->setMardi($mardi);
            $instance->setMercredi($mercredi);
            $instance->setJeudi($jeudi);
            $instance->setVendredi($vendredi);
            $instance->setSamedi($samedi);
            $instance->setDimanche($dimanche);
            $instance->setDateDebut($dateDebut);
            $instance->setDateFin($dateFin);
            return $instance;
        }
    
        public function getID(): int { return $this->ID; }
        public function getNom(): string { return $this->NOM; }
        public function isLundi(): bool { return $this->LUNDI; }
        public function isMardi(): bool { return $this->MARDI; }
        public function isMercredi(): bool { return $this->MERCREDI; }
        public function isJeudi(): bool { return $this->JEUDI; }
        public function isVendredi(): bool { return $this->VENDREDI; }
        public function isSamedi(): bool { return $this->SAMEDI; }
        public function isDimanche(): bool { return $this->DIMANCHE; }
        public function getDateDebut(): string { return $this->DATE_DEBUT; }
        public function getDateFin(): string { return $this->DATE_FIN; }

        public function setNom(string $nom): void { $this->NOM = $nom; }
        public function setLundi(bool $lundi): void { $this->LUNDI = $lundi; }
        public function setMardi(bool $mardi): void { $this->MARDI = $mardi; }
        public function setMercredi(bool $mercredi): void { $this->MERCREDI = $mercredi; }
        public function setJeudi(bool $jeudi): void { $this->JEUDI = $jeudi; }
        public function setVendredi(bool $vendredi): void { $this->VENDREDI = $vendredi; }
        public function setSamedi(bool $samedi): void { $this->SAMEDI = $samedi; }
        public function setDimanche(bool $dimanche): void { $this->DIMANCHE = $dimanche; }
        public function setDateDebut(string $dateDebut): void { $this->DATE_DEBUT = $dateDebut; }
        public function setDateFin(string $dateFin): void { $this->DATE_FIN = $dateFin; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM SERVICE");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(int $id): ?Service {
            $this->buildRequest("SELECT * FROM SERVICE WHERE ID = ?");
            $this->request->execute([$id]);
    
            return $this->request->fetchObject(self::class);
        }

        public function create(): int {
            $this->buildRequest("INSERT INTO SERVICE (NOM, LUNDI, MARDI, MERCREDI, JEUDI, VENDREDI, SAMEDI, DIMANCHE, DATE_DEBUT, DATE_FIN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $this->request->execute([
                $this->getNom(),
                $this->isLundi(),
                $this->isMardi(),
                $this->isMercredi(),
                $this->isJeudi(),
                $this->isVendredi(),
                $this->isSamedi(),
                $this->isDimanche(),
                $this->getDateDebut(),
                $this->getDateFin()
            ]);

            return $this->getLastID();
        }

        public function findDateService(): array {
            $this->buildRequest("SELECT * FROM DATES_SERVICE_AVEC_EXCEPTIONS");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, get_class(new class {
                private int $service_id;
                private string $nom;
                private string $date;

                public function getServiceId(): int { return $this->service_id; }
                public function getNom(): string { return $this->nom; } 
                public function getDate(): string { return $this->date; }
            }));
        }
    }

    class Trajet extends Request {
        private string $TRAJET_ID;
        private int $SERVICE_ID;
        private int $ITINERAIRE_ID;
        private int $DIRECTION;
    
        public function __construct() {
            parent::__construct();
        }

        public static function withData(string $trajetId, int $serviceId, int $itineraireId, int $direction): self {
            $instance = new self();
            $instance->setTrajetId($trajetId);
            $instance->setServiceId($serviceId);
            $instance->setItineraireId($itineraireId);
            $instance->setDirection($direction);
            return $instance;
        }
    
        public function getTrajetId(): string { return $this->TRAJET_ID; }
        public function getServiceId(): int { return $this->SERVICE_ID; }
        public function getItineraireId(): int { return $this->ITINERAIRE_ID; }
        public function getDirection(): int { return $this->DIRECTION; }

        public function setTrajetId(string $trajetId): void { $this->TRAJET_ID = $trajetId; }
        public function setServiceId(int $serviceId): void { $this->SERVICE_ID = $serviceId; }
        public function setItineraireId(int $itineraireId): void { $this->ITINERAIRE_ID = $itineraireId; }
        public function setDirection(int $direction): void { $this->DIRECTION = $direction; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM TRAJET");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(string $trajetId): ?Trajet {
            $this->buildRequest("SELECT * FROM TRAJET WHERE TRAJET_ID = ?");
            $this->request->execute([$trajetId]);
    
            return $this->request->fetchObject(self::class);
        }

        public function create(): int {
            $this->buildRequest("INSERT INTO TRAJET (TRAJET_ID, ITINERAIRE_ID, SERVICE_ID, DIRECTION) VALUES (?, ?, ?, ?)");
            $this->request->execute([
                $this->getTrajetId(),
                $this->getItineraireId(),
                $this->getServiceId(),
                $this->getDirection()
            ]);

            return $this->getLastID();
        }

        public function findAvgTime(): array {
            $this->buildRequest("SELECT * FROM AVG_TIME_ITINERAIRE");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, get_class(new class {
                private int $ITINERAIRE_ID;
                private string $TRAJET_ID;
                private int $AVG_STOP_TIME;

                public function getItineraireId(): int { return $this->ITINERAIRE_ID; }
                public function getTrajetId(): string { return $this->TRAJET_ID; }
                public function getAvgStopTime(): int { return $this->AVG_STOP_TIME; }
            }));
        }
    }

    class Itineraire extends Request {
        private int $ID;
        private ?int $AGENCE_ID;
        private string $TYPE;
        private string $NOM;
    
        public function __construct() {
            parent::__construct();
        }
    
        public static function withData(?int $agenceId, string $type, string $nom): self {
            $instance = new self();
            $instance->setAgenceId($agenceId);
            $instance->setType($type);
            $instance->setNom($nom);
            return $instance;
        }
    
        public function getId(): int { return $this->ID; }
        public function getAgenceId(): ?int { return $this->AGENCE_ID; }
        public function getType(): string { return $this->TYPE; }
        public function getNom(): string { return $this->NOM; }
    
        public function setAgenceId(?int $agenceId): void { $this->AGENCE_ID = $agenceId; }
        public function setType(string $type): void { $this->TYPE = $type; }
        public function setNom(string $nom): void { $this->NOM = $nom; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM ITINERAIRE");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findById(int $id): ?Itineraire {
            $this->buildRequest("SELECT * FROM ITINERAIRE WHERE ID = ?");
            $this->request->execute([$id]);
    
            return $this->request->fetchObject(self::class);
        }

        public function deleteById(int $id): void {
            $this->buildRequest("DELETE FROM ITINERAIRE WHERE ID = ?");
            $this->request->execute([$id]);
        }
    }

    class ArretDesservi extends Request {
        private int $ITINERAIRE_ID;
        private int $ARRET_ID;
        private int $SEQUENCE;
    
        public function __construct() {
            parent::__construct();
        }

        public static function withData(int $itineraireId, int $arretId, int $sequence): self {
            $instance = new self();
            $instance->setItineraireId($itineraireId);
            $instance->setArretId($arretId);
            $instance->setSequence($sequence);
            return $instance;
        }
    
        public function getItineraireId(): int { return $this->ITINERAIRE_ID; }
        public function getArretId(): int { return $this->ARRET_ID; }
        public function getSequence(): int { return $this->SEQUENCE; }

        public function setItineraireId(int $itineraireId): void { $this->ITINERAIRE_ID = $itineraireId; }
        public function setArretId(int $arretId): void { $this->ARRET_ID = $arretId; }
        public function setSequence(int $sequence): void { $this->SEQUENCE = $sequence; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM ARRET_DESSERVI");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findByItineraireId(int $itineraireId): array {
            $this->buildRequest("SELECT * FROM ARRET_DESSERVI WHERE ITINERAIRE_ID = ?");
            $this->request->execute([$itineraireId]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findByArretId(int $arretId): array {
            $this->buildRequest("SELECT * FROM ARRET_DESSERVI WHERE ARRET_ID = ?");
            $this->request->execute([$arretId]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    }

    class LanguePrincipale extends Request {
        private int $AGENCE_ID;
        private string $LANGUE;
    
        public function __construct() {
            parent::__construct();
        }

        public static function withData(int $agenceId, string $langue): self {
            $instance = new self();
            $instance->setAgenceId($agenceId);
            $instance->setLangue($langue);
            return $instance;
        }
    
        public function getAgenceId(): int { return $this->AGENCE_ID; }
        public function getLangue(): string { return $this->LANGUE; }

        public function setAgenceId(int $agenceId): void { $this->AGENCE_ID = $agenceId; }
        public function setLangue(string $langue): void { $this->LANGUE = $langue; }
    
        public function findAll(): array {
            $this->buildRequest("SELECT * FROM LANGUEPRINCIPALE");
            $this->request->execute([]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    
        public function findByAgenceId(int $agenceId): array {
            $this->buildRequest("SELECT * FROM LANGUEPRINCIPALE WHERE AGENCE_ID = ?");
            $this->request->execute([$agenceId]);
    
            return $this->request->fetchAll(PDO::FETCH_CLASS, self::class);
        }
    }
?>