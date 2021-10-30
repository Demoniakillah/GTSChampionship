<?php


namespace App\TwigExtension;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Maker;
use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\Team;
use App\Repository\DriverRepository;
use App\Repository\RaceRepository;
use App\Tool;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * RaceResult
 * RaceResult.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 04/06/2021
 */
class RaceResult extends AbstractExtension
{
    protected RaceRepository $raceRepository;

    protected DriverRepository $driverRepository;

    protected EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     * @return RaceResult
     */
    public function setEm(EntityManagerInterface $em): RaceResult
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @param DriverRepository $driverRepository
     * @return RaceResult
     */
    public function setDriverRepository(DriverRepository $driverRepository): RaceResult
    {
        $this->driverRepository = $driverRepository;
        return $this;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('bestLap', [$this, 'getBestLap']),
            new TwigFilter('podium', [$this, 'getPodium']),
            new TwigFilter('bestTeam', [$this, 'getBestTeam']),
            new TwigFilter('bestProgression', [$this, 'getBestProgression']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generalRanking', [$this, 'getGeneralRanking']),
        ];
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return string
     */
    public function getBestTeam($driverRaces): string
    {
        $teamPoints = [];
        $podium = $this->getPodium($driverRaces, true);
        foreach ($podium as $results) {
            foreach ($results as $result) {
                if ($result->getDriver()->getTeam() instanceof Team) {
                    if (isset($teamPoints[$result->getDriver()->getTeam()->getName()])) {
                        $teamPoints[$result->getDriver()->getTeam()->getName()] += $result->getPoints();
                    } else {
                        $teamPoints[$result->getDriver()->getTeam()->getName()] = $result->getPoints();
                    }
                }
            }
        }
        return array_search(max($teamPoints), $teamPoints, true);
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return array
     */
    public function getBestProgression($driverRaces): array
    {
        $driversProgression = [];
        $podium = $this->getPodium($driverRaces, true);
        foreach ($podium as $pool => $results) {
            foreach ($results as $finalRank => $result) {
                $driversProgression[$result->getDriver()->getPsn()] = $finalRank - $result->getStartPosition();
            }
        }
        return ['driver' => array_search(min($driversProgression), $driversProgression, true), 'progression' => min($driversProgression)];
    }

    /**
     * @param int $userGroupId
     * @return array
     */
    public function getGeneralRanking(int $userGroupId): array
    {
        $output = [
            'generalRanking' => [],
            'makerRanking' => [],
            'teamRanking' => [],
            'teamsProgression' => [],
            'driversProgression' => []
        ];

        /** @var Race[] $races */
        $races = $this->raceRepository->createQueryBuilder('race')
            ->where('race.date < :now')
            ->andWhere('race.userGroup = :userGroupId')
            ->setParameter('now', new \DateTime())
            ->setParameter('userGroupId', $userGroupId)
            ->orderBy('race.date', 'asc')
            ->getQuery()
            ->getResult();
        if (empty($races)) {
            return $output;
        }

        foreach ($races as $race) {
            foreach ($race->getDriverRaces() as $driverRace) {
                if ($driverRace->getDriver() instanceof Driver) {
                    if (isset($output['driversProgression'][$driverRace->getDriver()->getPsn()])) {
                        $output['driversProgression'][$driverRace->getDriver()->getPsn()] += $driverRace->getStartPosition() - $driverRace->getFinishPosition();
                    } else {
                        $output['driversProgression'][$driverRace->getDriver()->getPsn()] = $driverRace->getStartPosition() - $driverRace->getFinishPosition();
                    }

                    $driverId = $driverRace->getDriver()->getId();
                    if (isset($output['generalRanking'][$driverId])) {
                        $output['generalRanking'][$driverId] += $driverRace->getTotalTimeMilli();
                    } else {
                        $output['generalRanking'][$driverId] = $driverRace->getTotalTimeMilli();
                    }
                    if ($driverRace->getDriver()->getTeam() instanceof Team) {
                        $team = $driverRace->getDriver()->getTeam()->getName();
                        if (isset($output['teamsProgression'][$team])) {
                            $output['teamsProgression'][$team] += $driverRace->getStartPosition() - $driverRace->getFinishPosition();
                        } else {
                            $output['teamsProgression'][$team] = $driverRace->getStartPosition() - $driverRace->getFinishPosition();
                        }
                        $output['teamRanking'][$team]['count'] = isset($output['teamRanking'][$team]['count']) ? $output['teamRanking'][$team]['count'] + 1 : 1;
                        if (isset($output['teamRanking'][$team]['totalTimeRaw'])) {
                            $output['teamRanking'][$team]['totalTimeRaw'] += $driverRace->getTotalTimeMilli();
                        } else {
                            $output['teamRanking'][$team]['totalTimeRaw'] = $driverRace->getTotalTimeMilli();
                        }
                    }
                    if ($driverRace->getCar() instanceof Car && $driverRace->getCar()->getMaker() instanceof Maker) {
                        $maker = $driverRace->getCar()->getMaker()->getName();
                        $output['makerRanking'][$maker]['count'] = isset($output['makerRanking'][$maker]['count']) ? $output['makerRanking'][$maker]['count'] + 1 : 1;
                        if (isset($output['makerRanking'][$maker]['totalTimeRaw'])) {
                            $output['makerRanking'][$maker]['totalTimeRaw'] += $driverRace->getTotalTimeMilli();
                        } else {
                            $output['makerRanking'][$maker]['totalTimeRaw'] = $driverRace->getTotalTimeMilli();
                        }
                    }
                }
            }
        }


        $drivers = $this->driverRepository->findBy(['userGroup' => $userGroupId, 'id' => array_keys($output['generalRanking'])]);
        foreach ($drivers as $index => $driver) {
            $drivers[$driver->getId()] = $driver;
            unset($drivers[$index]);
        }

        foreach ($races[count($races) - 1]->getDriverRaces() as $driverRace) {
            $drivers[$driverRace->getDriver()->getId()]->setPool($driverRace->getPool());
        }
        $this->em->flush();

        foreach ($output['generalRanking'] as $driverId => $totalTime) {
            $output['generalRanking'][$driverId] = [
                'totalTime' => Tool::milliToTime($totalTime),
                'driver' => $drivers[$driverId]
            ];
        }

        $maxDrivers = 0;
        foreach ($output['teamRanking'] as $team => $data) {
            if ($maxDrivers < $data['count']) {
                $maxDrivers = $data['count'];
            }
        }

        foreach ($output['teamRanking'] as $team => $data) {
            $output['teamRanking'][$team]['factor'] = $maxDrivers / $data['count'];
            $output['teamRanking'][$team]['totalTimeFactorizedMilli'] = $output['teamRanking'][$team]['totalTimeRaw'] * $output['teamRanking'][$team]['factor'];
            $output['teamRanking'][$team]['totalTimeFactorized'] = Tool::milliToTime($output['teamRanking'][$team]['totalTimeFactorizedMilli']);
        }

        foreach ($output['makerRanking'] as $maker => $data) {
            $output['makerRanking'][$maker]['factor'] = $maxDrivers / $data['count'];
            $output['makerRanking'][$maker]['totalTimeFactorizedMilli'] = $output['makerRanking'][$maker]['totalTimeRaw'] * $output['makerRanking'][$maker]['factor'];
            $output['makerRanking'][$maker]['totalTimeFactorized'] = Tool::milliToTime($output['makerRanking'][$maker]['totalTimeFactorizedMilli']);
        }

        asort($output['generalRanking']);
        uasort($output['driversProgression'], static function ($a, $b) {
            return $a < $b;
        });
        uasort($output['teamsProgression'], static function ($a, $b) {
            return $a < $b;
        });
        uasort($output['teamRanking'], static function ($a, $b) {
            return $a['totalTimeFactorizedMilli'] > $b['totalTimeFactorizedMilli'];
        });
        uasort($output['makerRanking'], static function ($a, $b) {
            return $a['totalTimeFactorizedMilli'] > $b['totalTimeFactorizedMilli'];
        });

        $output['bestMaker'] = array_key_first($output['makerRanking']);
        $output['bestTeam'] = array_key_first($output['teamRanking']);
        $bestDriver = $output['generalRanking'][array_key_first($output['generalRanking'])]['driver'];
        $bestProgressionDriver = $this->driverRepository->findOneBy(['psn'=>array_key_first($output['driversProgression']), 'userGroup'=>$userGroupId]);
        if($bestProgressionDriver instanceof Driver){
            $output['bestDriverProgression'] = $bestProgressionDriver->getPsn();
            if(is_int($bestProgressionDriver->getNumber())){
                $output['bestDriverProgression'] .= ' #' . $bestProgressionDriver->getNumber();
            }
            if($bestProgressionDriver->getTeam() instanceof Team){
                $output['bestDriverProgression'] .= ' - ' . $bestProgressionDriver->getTeam()->getName();
            }
        }
        $output['bestTeamProgression'] = array_key_first($output['teamsProgression']);
        $output['bestDriver'] = $bestDriver->getPsn();
        $output['bestDriver'] .= (is_int($bestDriver->getNumber())) ? ' #' . $bestDriver->getNumber() : '';
        $output['bestDriver'] .= ($bestDriver->getTeam() instanceof Team) ? ' - ' . $bestDriver->getTeam()->getName() : '';

        return $output;
    }

    public function getRaceResults(PersistentCollection $driverRaces): array
    {
        return $this->getPodium($driverRaces, true);
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @param bool $full
     * @return DriverRace[]
     */
    public function getPodium($driverRaces, bool $full = false): array
    {
        $poolPriority = [];
        $podiumByPool = [];
        $driverRacesByPool = [];
        foreach ($driverRaces as $i => $driverRace) {
            if ($driverRace->isValid()) {
                $driverRacesByPool[$driverRace->getPool()->getName()][$i] = $driverRace;
            }
        }

        foreach ($driverRacesByPool as $pool => $poolDriverRaces) {
            $poolPriority[$pool] = current($poolDriverRaces)->getPool();
            $tmp = $poolDriverRaces;
            if (!empty($tmp)) {
                uasort(
                    $tmp,
                    static function (DriverRace $a, DriverRace $b) {
                        return Tool::timeToMilli($a->getTotalTime()) > Tool::timeToMilli($b->getTotalTime());
                    }
                );

                $tmp = array_values($tmp);
                $output = [];
                foreach ($tmp as $index => $driverRace) {
                    if ($full === false && $index > 2) {
                        break;
                    }
                    $output[] = $driverRace;
                }
                $podiumByPool[$pool] = $output;

            }
        }
        uasort($poolPriority, static function (Pool $a, Pool $b) {
            return $a->getPriority() > $b->getPriority();
        });
        foreach (array_keys($poolPriority) as $pool) {
            $poolPriority[$pool] = $podiumByPool[$pool];
        }

        return $poolPriority;
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return DriverRace
     */
    public function getBestLap($driverRaces): ?DriverRace
    {
        if ($driverRaces instanceof PersistentCollection) {
            $driverRaces = $driverRaces->toArray();
        }
        $driverRaces = array_filter($driverRaces, static function (DriverRace $a) {
            return $a->getBestLap() !== '00:00.000' && $a->hasBeenValidated();
        });
        usort($driverRaces, static function (DriverRace $a, DriverRace $b) {
            return (int)str_replace(':', '', $a->getBestLap()) > (int)str_replace(':', '', $b->getBestLap());
        });
        return $driverRaces[0] ?? null;
    }

    /**
     * @param RaceRepository $raceRepository
     * @return RaceResult
     */
    public function setRaceRepository(RaceRepository $raceRepository): RaceResult
    {
        $this->raceRepository = $raceRepository;
        return $this;
    }

    /**
     * @param DriverRace[] $driverRaces
     * @return int
     */
    protected function getMaxTime($driverRaces): int
    {
        $maxTime = 0;
        foreach ($driverRaces as $driverRace) {
            $timeToMilli = $driverRace->getRaceTimeMilli();
            if ($timeToMilli > $maxTime) {
                $maxTime = $timeToMilli;
            }
        }
        return $maxTime;
    }
}