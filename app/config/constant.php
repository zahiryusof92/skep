<?php

return [
    'module' => [
        'auth' => [
            'sso' => [
                'update_profile_url' => "https://patrick.odesi.tech/api/admin/auth/profile/update_simple"
            ]
        ],
        'finance' => [
            'tabs' => [
                'main' => [
                    'message_fields' => [
                        'file_no',
                        'from_api',
                        'report.sf',
                        'report.mf',
                        'income.main',
                        'utility.bhg_a',
                        'utility.bhg_a.main',
                        'utility.bhg_b',
                        'utility.bhg_b.main',
                        'contract.main',
                        'repair',
                        'repair.mf',
                        'repair.mf.main',
                        'repair.sf',
                        'repair.sf.main',
                        'vandal',
                        'vandal.mf',
                        'vandal.mf.main',
                        'vandal.sf',
                        'vandal.sf.main',
                        'staff.main',
                        'admin.main',
                    ],
                    'only' => [
                        'file_no',
                        'year',
                        'month',
                        'from_api'
                    ],
                    'special_validation' => [
                        'file_no' => 'required',
                        'year' => 'required|numeric',
                        'month' => 'required|numeric',
                        'from_api' => 'required',
                        'check' => 'required',
                        // 'summary' => 'required',
                        'report' => 'required',
                        'report.mf' => 'required',
                        'report.sf' => 'required',
                        'income' => 'required',
                        'income.main' => 'required',
                        'utility' => 'required',
                        'utility.bhg_a' => 'required',
                        'utility.bhg_a.main' => 'required',
                        'utility.bhg_b' => 'required',
                        'utility.bhg_b.main' => 'required',
                        'contract' => 'required',
                        'contract.main' => 'required',
                        'repair' => 'required',
                        'repair.mf' => 'required',
                        'repair.mf.main' => 'required',
                        'repair.sf' => 'required',
                        'repair.sf.main' => 'required',
                        'vandal' => 'required',
                        'vandal.mf' => 'required',
                        'vandal.mf.main' => 'required',
                        'vandal.sf' => 'required',
                        'vandal.sf.main' => 'required',
                        'staff' => 'required',
                        'staff.main' => 'required',
                        'admin' => 'required',
                        'admin.main' => 'required',
                    ]
                ],
                'check' => [
                    'name' => 'check',
                    'message_fields' => [
                        'date',
                        'position',
                        'name',
                        'finance_file_id',
                    ],
                    'only' => [
                        'date',
                        'name',
                        'position',
                        'is_active',
                        'remarks',
                    ]
                ],
                'admin' => [
                    'prefix' => 'admin_',
                    'name' => 'admin',
                    'default' => [
                        'telefon_n_internet',
                        'peralatan',
                        'alat_tulis_perjabat',
                        'petty_cash',
                        'sewaan_mesin_fotokopi',
                        'perkhid_sistem_ubs_n_lain',
                        'perkhid_akaun',
                        'perkhid_audit',
                        'caj_perundangan',
                        'caj_penghantaran_n_kutipan',
                        'caj_bank',
                        'fi_ejen_pengurusan',
                        'perbelanjaan_mesyuarat',
                        'elaun_jmb_mc',
                        'lain_lain_tuntutan_jmb_mc',
                    ],
                    'only' => [
                        'default',
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak'
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'contract' => [
                    'prefix' => 'contract_',
                    'name' => 'contract',
                    'default' => [
                        'fi_firma_kompeten_lif',
                        'pembersihan_kontrak',
                        'keselamatan',
                        'insurans',
                        'jurutera_elektrik',
                        'cuci_tangki_air',
                        'uji_penggera_kebakaran',
                        'cuci_kolam_renang',
                        'sedut_pembetung',
                        'potong_rumput_lanskap',
                        'sistem_kad_akses',
                        'sistem_cctv',
                        'uji_peralatan_pemadam_kebakaran',
                        'kutipan_sampah_pukal',
                        'kawalan_serangga',
                    ],
                    'only' => [
                        'default',
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'income' => [
                    'name' => 'income',
                    'prefix' => 'income_',
                    'default' => [
                        'maintain_fee',
                        'sinking_fund',
                        'insuran_bangunan',
                        'cukai_tanah',
                        'pelekat_kenderaan',
                        'kad_akses',
                        'sewaan_tlk',
                        'sewaan_kedai',
                        'sewaan_harta_bersama',
                        'denda_undang_undang_kecil',
                        'denda_lewat_bayar_maintain_sinking_fee',
                        'bil_meter_air_pemilik_pemilik',
                    ],
                    'only' => [
                        'default',
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'summary' => [
                    'name' => 'summary',
                    'prefix' => 'sum_',
                    'message_fields' => [
                        'finance_file_id',
                        'bill_air',
                        'bill_elektrik',
                        'caruman_insuran',
                        'caruman_cukai',
                        'fi_firma',
                        'pembersihan',
                        'keselamatan',
                        'jurutera_elektrik',
                        'mechaninal',
                        'civil',
                        'kawalan_serangga',
                        'kos_pekerja',
                        'pentadbiran',
                        'fi_ejen_pengurusan',
                        'lain_lain',
                    ],
                    'only' => [
                        'bill_air',
                        'bill_elektrik',
                        'caruman_insuran',
                        'caruman_cukai',
                        'fi_firma',
                        'pembersihan',
                        'keselamatan',
                        'jurutera_elektrik',
                        'mechaninal',
                        'civil',
                        'kawalan_serangga',
                        'kos_pekerja',
                        'pentadbiran',
                        'fi_ejen_pengurusan',
                        'lain_lain',
                    ]
                ],
                'staff' => [
                    'prefix' => 'staff_',
                    'name' => 'staff',
                    'default' => [
                        'pegawal_keselamatan',
                        'pembersihan',
                        'rencam',
                        'kerani',
                        'juruteknik',
                        'penyelia',
                    ],
                    'only' => [
                        'default',
                        'name',
                        'gaji_per_orang',
                        'bil_pekerja',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'repair' => [
                    'name' => 'repair',
                    'prefix' => 'repair_',
                    'type' => [
                        'mf' => [
                            'prefix' => 'repair_maintenancefee_',
                            'name' => 'MF',
                            'default' => [
                                'lif',
                                'tangki_air',
                                'bumbung',
                                'gutter',
                                'rain_water_down_pipe',
                                'pembentung',
                                'perpaipan',
                                'wayar_bumi',
                                'pendawaian_elektrik',
                                'tangga_handrail',
                                'jalan',
                                'pagar',
                                'longkang',
                                'substation_tnb',
                                'alat_pemadam_kebakaran',
                                'sistem_kad_akses',
                                'cctv',
                                'pelekat_kenderaan',
                                'genset'
                            ],
                        ],
                        'sf' => [
                            'prefix' => 'repair_singkingfund_',
                            'name' => 'SF',
                            'default' => [
                                'lif',
                                'tangki_air',
                                'bumbung',
                                'gutter',
                                'rain_water_down_pipe',
                                'pembentung',
                                'perpaipan',
                                'wayar_bumi',
                                'pendawaian_elektrik',
                                'tangga_handrail',
                                'jalan',
                                'pagar',
                                'longkang',
                                'substation_tnb',
                                'alat_pemadam_kebakaran',
                                'sistem_kad_akses',
                                'cctv',
                                'genset'
                            ]
                        ]
                    ],
                    'only' => [
                        "default",
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'report' => [
                    'name' => 'report',
                    'prefix' => 'report_',
                    'type' => [
                        'mf' => [
                            'prefix' => 'mfr_',
                            'name' => 'MF',
                            'only' => [
                                'utility',
                                'contract',
                                'repair',
                                'vandalisme',
                                'staff',
                                'admin',
                            ]
                        ],
                        'sf' => [
                            'prefix' => 'sfr_',
                            'name' => 'SF',
                            'only' => [
                                'repair',
                                'vandalisme'
                            ]
                        ]
                    ],
                    'only' => [
                        'fee_sebulan',
                        'unit',
                        'fee_semasa',
                        'no_akaun',
                        'nama_bank',
                        'baki_bank_awal',
                        'baki_bank_akhir',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'nama_bank' => 'required'
                    ]
                ],
                'utility' => [
                    'name' => 'utility',
                    'prefix' => 'utility_',
                    'type' => [
                        'bhg_a' => [
                            'prefix' => 'util_',
                            'name' => 'BHG_A',
                            'default' => [
                                'bil_air',
                                'bil_elektrik',
                            ],
                        ],
                        'bhg_b' => [
                            'prefix' => 'utilb_',
                            'name' => 'BHG_B',
                            'default' => [
                                'bil_meter_air',
                                'bil_cukai_tanah',
                            ],
                        ]
                    ],
                    'only' => [
                        'default',
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
                'vandal' => [
                    'name' => 'vandal',
                    'prefix' => 'vandal_',
                    'type' => [
                        'mf' => [
                            'prefix' => 'maintenancefee_',
                            'name' => 'MF',
                            'default' => [
                                'lif',
                                'wayar_bumi',
                                'pendawaian_elektrik',
                                'pagar',
                                'substation_tnb',
                                'peralatan_pemadam_kebakaran',
                                'sistem_kad_akses',
                                'cctv',
                                'genset'
                            ]
                        ],
                        'sf' => [
                            'prefix' => 'singkingfund_',
                            'name' => 'SF',
                            'default' => [
                                'lif',
                                'wayar_bumi',
                                'pendawaian_elektrik',
                                'pagar',
                                'substation_tnb',
                                'peralatan_pemadam_kebakaran',
                                'sistem_kad_akses',
                                'cctv',
                                'genset'
                            ]
                        ]
                    ],
                    'only' => [
                        'default',
                        'name',
                        'tunggakan',
                        'semasa',
                        'hadapan',
                        'tertunggak',
                    ],
                    'extra' => [
                        'is_custom'
                    ],
                    'special_validation' => [
                        'default' => 'required',
                        'name' => 'required'
                    ]
                ],
            ]
        ],
        'cob' => [
            'facility' => [
                'management_office' => [
                    'name' => 'management_office',
                    'title' => 'Management Office'
                ],
                'swimming_pool' => [
                    'name' => 'swimming_pool',
                    'title' => 'Swimming Pool'
                ],
                'surau' => [
                    'name' => 'surau',
                    'title' => 'Surau'
                ],
                'multipurpose_hall' => [
                    'name' => 'multipurpose_hall',
                    'title' => 'Multipurpose Hall'
                ],
                'gym' => [
                    'name' => 'gym',
                    'title' => 'Gym'
                ],
                'playground' => [
                    'name' => 'playground',
                    'title' => 'Playground'
                ],
                'guardhouse' => [
                    'name' => 'guardhouse',
                    'title' => 'Guard House'
                ],
                'kindergarten' => [
                    'name' => 'kindergarten',
                    'title' => 'KinderGarten'
                ],
                'open_space' => [
                    'name' => 'open_space',
                    'title' => 'Open Space'
                ],
                'lift' => [
                    'name' => 'lift',
                    'title' => 'Lift'
                ],
                'rubbish_room' => [
                    'name' => 'rubbish_room',
                    'title' => 'Rubbish Room'
                ],
                'gated' => [
                    'name' => 'gated',
                    'title' => 'Gated'
                ],
            ]
        ],
        'auth' => [
            'sso' => [
                'update_profile_url' => "https://patrick.odesi.tech/api/admin/auth/profile/update_simple"
            ]
        ],
        'payment' => [
            'prefix' => 'eCobS',
            'gateway' => [
                'paydibs' => [
                    'slug' => 'paydibs',
                    'title' => 'Paydibs',
                    'merchant_id' => 'TEST',
                    'merchant_password' => 'test12345',
                    'pay_request_url' => 'https://dev.paydibs.com/PPGSG/PymtCheckout.aspx',
                ],
                'revenue' => [
                    'slug' => 'revenue',
                    'title' => 'Revenue Monster',
                    'client_id' => '1618562074490000151',
                    'client_secret' => 'RFrAQJJaIvTIZKfVWWCuELucWoPILCLs',
                    'store_id' => '1618295254553642125',
                    'nonceStr' => 'MNhrdDgIDlTKdlYXzfhEvqHpGjRhvrPb',
                    'private_key' => "-----BEGIN RSA PRIVATE KEY-----\nMIIEoQIBAAKCAQB55lfJiCvYt8754qQ0lQP67mJuT+CziOD4znJqqNXZYp+9ANSw\nFOb2zkMEcmwWCaWHc6Nf7AlTCaHhFK6VV7wp9Hpn1ifIShrPiMhpaTldvyUFJI+t\n1y8QLPncvEHgJClKRzQUwsRzt8zB1h6Is7r6qnrOvSq1li4QTo4OVKKrJ8Ge3l5V\nFo8iYx1XQYYLuw4QuhEdUTL8w6x0HMjZv8qqWOCT71YvdrV6sz8ip8ksJ3hO07NL\nEhtXBcO9Q4ll9+1UkoyaQUdenBbjSaYC+L4FPh8BK9Zbiv6SLNeW6o2SU5NTUz8A\n9NzmhHg4rg83KJAZd6byNFHq9+rIrarXH18ZAgMBAAECggEAQCjKv9oaKeDk4YU2\npC5if+qEiEcmn5hFwO74LcdbdyV/qf0L9Pmrbu+1Olv3I8pEA30L2mrjpX8GYu99\nzED3WqXA33JKUhMe9WirA0BM31jB5WkO7pK5xKvHfzTe/1hJGz1K3jp9J+LvtH48\nZ/shSNn99V5/Yj//Urnl7wkA7VAsVoXO/ddSJspdVTUwCBjn21Lo+eekY1YAJZvM\nv2Eo+tppDdUGBQk5DzvMaX0u6TynTecvYvulD4ydEnp0AHLYxuX+AaAI6gDSpU34\nu0LHrNnFl9OZN/5ILhiSLidbT5dNpd/tGJwsf6BwJgY4mkRId/agEKhjnzGDyYCN\ndhyUuQKBgQDU4qT0p2BsqVyBiIxZYnjMlxQvo20J5hjrrbzCg9XutrKJBB8THNgd\nFFvB9lXvc4ANCSejHBKCEZ+RTAnUNYckDEI2qikGyDF5LLXtacCcNJ0OLxzT1L/H\nGoWmc+3CURCHjGjwv8ZKZAWIlk95UZesudLaDQpQFjx3+D8nzJ4YiwKBgQCSlmrr\nGUM/p1FbnSwWstctczcW1M0P93a7SSxlyIDe72TxmjA1lHGEE3sZr0iwdEZXOtFx\njbCtY7noaU19eeSgxwJjNeMQtAfQrwRsyQwsrtIPR6Mm1gApM0eoyDH+7olRtZEX\nUOvMrnvo5FwDVO15b6bCa9UtKUkHtpZUNLf3awKBgGRvVRGTkbtSH7GBAW3+Ni2X\nrU9YU84Sv3kZK2+9kQWhLhPUXPDAb/kwLKYSmLJylFwSEKA0U0sIE3cSH6T9Z/eK\nzQZLOzXW5vhbLq2GcfeAHwSdF4Wc3qmJirpgATDm29yHUs8aOuvHypCJEY+T/fHg\nbWiwNEhaZUbYWLtnHsNHAoGARbWedtr2rgilmgmuc8Tsq0KFSig8tIEcR4hOwsCM\n+Hej63lFv9Ut/n89JUPEL5W79bOU8olNIcexGyMopKkY3pSFQCTaLJFQvh7c3dbm\nIUGpx3Zr0gYm4XDvfQZnMDLDvsW3j/z9hcLcTi56y2LR49QC26ZoJCfkAPUFnFnN\nuq0CgYBuH8wVI62GWQITMwiCePOrXxDKPcD7isize5vDnXyYlhtVHdf9MiHIryqy\n0LLJC9EGlbQVoTti1djUD5jFG+rw79CEy/UJpnnqaEXhinJLmgXzLe2oxcLsg7f3\n/n1nryTv7s0dYbztRWoG+NHqOhDCrajMa7iOSrLVf53QZpPXCA==\n-----END RSA PRIVATE KEY-----",
                    'public_key' => "-----BEGIN PUBLIC KEY-----\nMIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQB55lfJiCvYt8754qQ0lQP6\n7mJuT+CziOD4znJqqNXZYp+9ANSwFOb2zkMEcmwWCaWHc6Nf7AlTCaHhFK6VV7wp\n9Hpn1ifIShrPiMhpaTldvyUFJI+t1y8QLPncvEHgJClKRzQUwsRzt8zB1h6Is7r6\nqnrOvSq1li4QTo4OVKKrJ8Ge3l5VFo8iYx1XQYYLuw4QuhEdUTL8w6x0HMjZv8qq\nWOCT71YvdrV6sz8ip8ksJ3hO07NLEhtXBcO9Q4ll9+1UkoyaQUdenBbjSaYC+L4F\nPh8BK9Zbiv6SLNeW6o2SU5NTUz8A9NzmhHg4rg83KJAZd6byNFHq9+rIrarXH18Z\nAgMBAAE=\n-----END PUBLIC KEY-----",
                    'sandbox_client_id' => '1619065365617468809',
                    'sandbox_client_secret' => 'PNvjbdlJbDYBdSjaqlQnFmcflhKhoyPA',
                    'sandbox_private_key' => "-----BEGIN RSA PRIVATE KEY-----\nMIIEpQIBAAKCAQEAqQPPaMdnF2EQ5sSuLCzPTKTjRxIPrKVJQNG37Z8I6Y9SnUet\nvnfJi3fGdultAI7nqzzILkXS5gcJXFomeAefJohvPI5xBNAeUZUYvRcGTcynR1ic\noLOCnBy7sXe54pXA0Hz1KSAprmzgAkWGPozKLRKG2pjINVyCMwku5AKwM/EeY1Ad\nzRiaiUqnWYDSLWFFSgSh7EWqbGbUzLJWpGmYzIDnvLgSDTd08b2YvQB5i67LWB1w\ntasI7eUZTiPWZwuKmg021UuYJcuhGCzs9DIpPysQUFDZpzni5bEOu60czhch3XaZ\nQ+MuzXy7m412tS2jUyCS5PjMP+kS9Brovp4PgQIDAQABAoIBAQCeSLM1rYkenAj4\nFboIJO7/GGzBYmjLhv/TdJSMNSRjl5YHNdnBRu25b7KuKm2q2Cqec44Fx957Dpv1\noXflcT3bGGsUi1HM8Bt0y+0fKTD+K2t9pb8JwRPQoBgnW7j6OsUYe+4dVcGzWMNx\nXSsb6T5ud6tTjUHyt3A326HOZ7Yc7W2g6eXpjDC7bDNA15XIT4ZH52yijtC9EN0r\neUVGx2k8FuYZcGTIROI+V4BneocoYHEiiZT4/sVksPZ7uTDczWmD6holPhUk15PH\n1Mjk5krvlp+wgFJwefLmJptum54gfqKw0PbZtJZSWxFCcuqgAYUjePW8x2uGP82m\np+RnDr8BAoGBANMW23SYKGo0UmvZgNugFvqWbyfOCTCYvx9iK5XQYbSDDv3gQ36p\n0bvQEEpRctqAk++R7wlJrFj2u5r4BdUzrMnhAnWwGBC9sAGqH9zwbilMdhANniQM\nXSes1lmp/jDM9V3MNT0QcKyX4svrdDlDHt9dyD6hTMUO/RbdfI5XIgGvAoGBAMz5\nVne9rAIeZGrUCtA+63cu/RlxRmfkVR0BhgQZHdkPhHUBMy6Dhhz0luNhKds6MF/m\nPJZktHvDaj9rAcoeSeef3gygbb8NbVawnjXye/38+lfJ59GykZKDObunegODA5Z9\nJwi8echjCvjoaBFSG2yDXhjHSZ/Tk89ZIaTRyD3PAoGBAMcymbPkZrcaWvEllsEq\nyKR+07RXbTqr2ifuhzzgIWOsDbisKmVcMeyvQsAvLlhd37zDFTTJjleBtrZd+Xsh\nQZxctuqCW6NorbBUb68aQIq+eedaQnK4LCxloP2KOrKop591NoRCgGGPD1np/oqO\n+ZJFuNLJel23cERkjMbyGMhHAoGBAK0MIA0Na4eKYxqxk8OGK0K+v91X/am+YoEP\nhTY2gjV73Ubu0WgGkBYoBK9Im47CzI5aEXKi6KDINQ3/aE5cTean2E5urtiqHFIg\n4CEJa9bdWfj9s6IdELtR4E7mMKOAW2oUlPzSKjqcK2pCw4FEAXUWXz2qGiU9+b8H\nL9k3ZsePAoGAK7DwhqLeMb4uLXWU2ZLUuFHdezvZKedAU6/u/IMEeckxMjs8ZF1K\nv/7DHduYww4Lgs+gqhO+1Y6utSdgYO08qlos8QhWiCLRHROaQlUAHrq2tAlYLrpc\n5PbJXF4hgdk4Q8+TL0nrmLj8UvFUhsq5LrnjcDEGfe/7feEeEM55htI=\n-----END RSA PRIVATE KEY-----",
                    'sandbox_store_id' => '1619057604454175171'
                ],
            ],
            'pay_for' => [
                'letter_of_reminder' => [
                    'slug' => 'letter_of_reminder',
                    'title' => 'Letter Of Reminder',
                ],
                'letter_of_demand' => [
                    'slug' => 'letter_of_demand',
                    'title' => 'Letter Of Demand',
                ],
                'point' => [
                    'slug' => 'point',
                    'title' => 'Point',
                ],
            ],
            'status' => [
                '0' => 'successful',
                '1' => 'fail',
                '2' => 'pending',
                '3' => 'refunded',
            ]

        ]
    ],
    'eai' => [
        'domain' => 'http://127.0.0.1:94/api/v1/',
        'route' => [
            'auth' => [
                'login' => 'auth/login',
            ],
            'file' => [
                'cob' => [
                    'add' => 'file/cob/add',
                    'import' => 'file/cob/import',
                    'status_update' => 'file/cob/status/update',
                    'fileNo_update' => 'file/cob/fileNo/update',
                    'delete' => 'file/cob/delete',
                    'prefix' => [
                        'add' => 'file/cob/prefix/add',
                        'update' => 'file/cob/prefix/update',
                        'status_update' => 'file/cob/prefix/status/update',
                        'delete' => 'file/cob/prefix/delete',
                    ],
                    'house' => [
                        'update' => 'file/cob/house/update',
                        'add_user' => 'file/cob/house/add_user',
                        'delete_user' => 'file/cob/house/delete_user',
                    ],
                    'strata' => [
                        'update' => 'file/cob/strata/update',
                        'file_upload' => 'file/cob/strata/file/upload',
                        'file_delete' => 'file/cob/strata/file/delete',
                    ],
                    'management' => [
                        'update' => 'file/cob/management/update',
                    ],
                    'monitoring' => [
                        'update' => 'file/cob/monitoring/update',
                        'agm_detail' => [
                            'add' => 'file/cob/monitoring/agm_detail/add',
                            'update' => 'file/cob/monitoring/agm_detail/update',
                            'delete' => 'file/cob/monitoring/agm_detail/delete',
                        ],
                        'ajk_detail' => [
                            'add' => 'file/cob/monitoring/ajk_detail/add',
                            'update' => 'file/cob/monitoring/ajk_detail/update',
                            'delete' => 'file/cob/monitoring/ajk_detail/delete',
                        ],
                        'upload' => [
                            'auditReportFile' => 'file/cob/monitoring/upload/auditReportFile',
                            'letterIntegrity' => 'file/cob/monitoring/upload/letterIntegrity',
                            'letterBankruptcy' => 'file/cob/monitoring/upload/letterBankruptcy',
                            'noticeAgmEgm' => 'file/cob/monitoring/upload/noticeAgmEgm',
                            'minutesAgmEgm' => 'file/cob/monitoring/upload/minutesAgmEgm',
                            'minutesAjk' => 'file/cob/monitoring/upload/minutesAjk',
                            'eligibleVote' => 'file/cob/monitoring/upload/eligibleVote',
                            'attendMeeting' => 'file/cob/monitoring/upload/attendMeeting',
                            'proksi' => 'file/cob/monitoring/upload/proksi',
                            'ajkInfo' => 'file/cob/monitoring/upload/ajkInfo',
                            'ic' => 'file/cob/monitoring/upload/ic',
                            'purchaseAggrement' => 'file/cob/monitoring/upload/purchaseAggrement',
                            'maintenanceStatement' => 'file/cob/monitoring/upload/maintenanceStatement',
                            'strataTitle' => 'file/cob/monitoring/upload/strataTitle',
                            'integrityPledge' => 'file/cob/monitoring/upload/integrityPledge',
                            'reportAuditedFinancial' => 'file/cob/monitoring/upload/reportAuditedFinancial',
                            'houseRules' => 'file/cob/monitoring/upload/houseRules',
                        ],
                        'delete' => [
                            'auditReportFile' => 'file/cob/monitoring/delete/auditReportFile',
                            'letterIntegrity' => 'file/cob/monitoring/delete/letterIntegrity',
                            'letterBankruptcy' => 'file/cob/monitoring/delete/letterBankruptcy',
                            'noticeAgmEgm' => 'file/cob/monitoring/delete/noticeAgmEgm',
                            'minutesAgmEgm' => 'file/cob/monitoring/delete/minutesAgmEgm',
                            'minutesAjk' => 'file/cob/monitoring/delete/minutesAjk',
                            'eligibleVote' => 'file/cob/monitoring/delete/eligibleVote',
                            'attendMeeting' => 'file/cob/monitoring/delete/attendMeeting',
                            'proksi' => 'file/cob/monitoring/delete/proksi',
                            'ajkInfo' => 'file/cob/monitoring/delete/ajkInfo',
                            'ic' => 'file/cob/monitoring/delete/ic',
                            'purchaseAggrement' => 'file/cob/monitoring/delete/purchaseAggrement',
                            'maintenanceStatement' => 'file/cob/monitoring/delete/maintenanceStatement',
                            'strataTitle' => 'file/cob/monitoring/delete/strataTitle',
                            'integrityPledge' => 'file/cob/monitoring/delete/integrityPledge',
                            'reportAuditedFinancial' => 'file/cob/monitoring/delete/reportAuditedFinancial',
                            'houseRules' => 'file/cob/monitoring/delete/houseRules',
                        ],
                    ],
                    'others' => [
                        'update' => 'file/cob/others/update',
                        'image_upload' => 'file/cob/others/image/upload',
                        'image_delete' => 'file/cob/others/image/delete',
                    ],
                    'scoring' => [
                        'add' => 'file/cob/scoring/add',
                        'update' => 'file/cob/scoring/update',
                        'delete' => 'file/cob/scoring/delete',
                    ],
                    'buyer' => [
                        'add' => 'file/cob/buyer/add',
                        'update' => 'file/cob/buyer/update',
                        'delete' => 'file/cob/buyer/delete',
                        'import' => 'file/cob/buyer/import',
                    ],
                    'document' => [
                        'add' => 'file/cob/document/add',
                        'update' => 'file/cob/document/update',
                        'delete' => 'file/cob/document/delete',
                        'file_upload' => 'file/cob/document/file/upload',
                        'file_delete' => 'file/cob/document/file/delete',
                    ],
                    'insurance' => [
                        'add' => 'file/cob/insurance/add',
                        'update' => 'file/cob/insurance/update',
                        'delete' => 'file/cob/insurance/delete',
                    ],
                    
                ],
            ],
            'user' => [
                'add' => 'user/add',
                'approve' => 'user/approve',
                'update' => 'user/update',
                'status_update' => 'user/status/update',
                'delete' => 'user/delete',
            ],
            'profile' => [
                'update' => 'profile/update',
                'password_update' => 'profile/password/update',
            ],
        ]
    ],
    'others' => [
        'status' => [
            'active' => [
                'slug' => 1
            ],
            'inactive' => [
                'slug' => 0
            ],
        ],
        'is_deleted' => [
            'true' => [
                'slug' => 1
            ],
            'false' => [
                'slug' => 0
            ]
        ],
        'messages' => [
            'file_no' => 'File No',
            'from_api' => 'From API',
            'finance_file_id' => 'Finance File Id',
            'finance_file_no' => 'Finance File No',
            'report.sf' => 'SF Report',
            'report.mf' => 'MF Report',
            'income.main' => 'Income Main',
            'utility.bhg_a' => 'Utility (Bahagian A)',
            'utility.bhg_a.main' => 'Utility (Bahagian A) Main Data',
            'utility.bhg_b' => 'Utility (Bahagian B)',
            'utility.bhg_b.main' => 'Utility (Bahagian B) Main Data',
            'contract.main' => 'Contract Main',
            'repair.mf' => 'Repair Maintainance Fee',
            'repair.mf.main' => 'Repair Maintainance Fee Main Data',
            'repair.sf' => 'Repair Singking Fund',
            'repair.sf.main' => 'Repair Singking Fund Main Data',
            'vandal' => 'Vandalisme',
            'vandal.mf' => 'Vandalisme Maintainance Fee',
            'vandal.mf.main' => 'Vandalisme Maintainance Fee Main Data',
            'vandal.sf' => 'Vandalisme Singking Fund',
            'vandal.sf.main' => 'Vandalisme Singking Fund Main Data',
            'staff.main' => 'Staff Main Data',
            'admin.main' => 'Admin Main Data',
            'date' => 'Date',
            'position' => 'Position',
            'name' => 'Name',
            'bill_air' => 'Bil Air',
            'bill_elektrik' => 'Bil. Elektrik',
            'caruman_insuran' => 'Caruman Insuran',
            'caruman_cukai' => 'Caruman Cukai Tanah',
            'fi_firma' => 'Fi Firma Kompeten Lif',
            'pembersihan' => 'Pembersihan',
            'keselamatan' => 'Keselamatan',
            'jurutera_elektrik' => 'Jurutera Elektrik',
            'mechaninal' => 'Mechaninal',
            'civil' => 'Civil & Structure',
            'kawalan_serangga' => 'Kawalan Serangga',
            'kos_pekerja' => 'Kos Pekerja',
            'pentadbiran' => 'Pentadbiran',
            'fi_ejen_pengurusan' => 'Fi Ejen Pengurusan',
            'lain_lain' => 'Lain-Lain',
            'utility' => 'Utility',
            'contract' => 'Contract',
            'repair' => 'Repair',
            'vandalisme' => 'Vandalisme',
            'staff' => 'Staff',
            'admin' => 'Admin',
            'fee_sebulan' => 'Fee Sebulan',
            'unit' => "Unit",
            'fee_semasa' => 'Fee Semasa',
            'no_akaun' => 'No Akaun',
            'nama_bank' => 'Nama Bank',
            'baki_bank_awal' => 'Baki Bank Awal',
            'baki_bank_akhir' => 'Baki Bank Akhir',
            'is_custom' => 'Custom Array',
            'default' => 'Default',
            'tunggakan' => 'Tunggakan',
            'semasa' => 'Semasa',
            'hadapan' => 'Hadapan',
            'tertunggak' => 'Tertunggak',
            'gaji_per_orang' => 'Gaji Per-Orang',
            'bil_pekerja' => 'Bil Pekerja',
        ],
        'tbl_fields_name' => [
            'income_maintain_fee' => 'MAINTENANCE FEE',
            'income_sinking_fund' => 'SINKING FUND',
            'income_insuran_bangunan' => 'INSURAN BANGUNAN',
            'income_cukai_tanah' => 'CUKAI TANAH',
            'income_pelekat_kenderaan' => 'PELEKAT KENDERAAN',
            'income_kad_akses' => 'KAD AKSES',
            'income_sewaan_tlk' => 'SEWAAN TLK',
            'income_sewaan_kedai' => 'SEWAAN KEDAI',
            'income_sewaan_harta_bersama' => 'SEWAAN HARTA BERSAMA',
            'income_denda_undang_undang_kecil' => 'DENDA UNDANG-UNDANG KECIL',
            'income_denda_lewat_bayar_maintain_sinking_fee' => 'DENDA LEWAT BAYAR MAINTENANCE FEE @ SINKING FUND',
            'income_bil_meter_air_pemilik_pemilik' => 'BIL METER AIR PEMILIK-PEMILIK(DI BAWAH AKAUN METER PUKAL SAHAJA)',
            'sum_bill_air' => 'Bil Air',
            'sum_bill_elektrik' => 'Bil. Elektrik',
            'sum_caruman_insuran' => 'Caruman Insuran',
            'sum_caruman_cukai' => 'Caruman Cukai Tanah',
            'sum_fi_firma' => 'Fi Firma Kompeten Lif',
            'sum_pembersihan' => 'Pembersihan Termasuk potong rumput, lanskap, kutipan sampah pukal dan lain-lain',
            'sum_keselamatan' => 'Keselamatan Termasuk Sistem CCTV, Palang Automatik, Kad Akses, Alat Pemadam Api, Penggera Kebakaran dan lain-lain	',
            'sum_jurutera_elektrik' => 'Jurutera Elektrik',
            'sum_mechaninal' => 'Mechaninal & Electrical Termasuk semua kerja-kerja penyenggaraan/ pembaikan /penggantian/ pembelian lampu, pendawaian elektrik, wayar bumi, kelengkapan lif, substation TNB,Genset dan lain-lain',
            'sum_civil' => 'Civil & Structure Termasuk semua kerja-kerja penyenggaraan/ pembaikan /penggantian/ pembelian tangki air, bumbung, kolam renang, pembentung, perpaipan, tangga, pagar, longkang dan lain-lain',
            'sum_kawalan_serangga' => 'Kawalan Serangga',
            'sum_kos_pekerja' => 'Kos Pekerja',
            'sum_pentadbiran' => 'Pentadbiran Termasuk telefon, internet, alat tulis pejabat, petty cash, sewaan mesin fotokopi, fi audit, caj bank dan lain-lain',
            'sum_fi_ejen_pengurusan' => 'Fi Ejen Pengurusan',
            'sum_lain_lain' => 'Lain-Lain-sekiranya ada Termasuk sila senaraikan',
            'report_utility' => 'UTILITI (BAHAGIAN A SAHAJA)',
            'report_contract' => 'PENYENGGARAAN',
            'report_repair' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN',
            'report_vandalisme' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN (VANDALISME)',
            'report_staff' => 'PEKERJA',
            'report_admin' => 'PENTADBIRAN',
            'utility_bil_air' => 'BIL AIR METER PUKAL',
            'utility_bil_elektrik' => 'BIL ELEKTRIK HARTA BERSAMA',
            'utility_bil_meter_air' => 'BIL METER AIR PEMILIK-PEMILIK (DI BAWAH AKAUN METER PUKAL SAHAJA)',
            'utility_bil_cukai_tanah' => 'BIL CUKAI TANAH',
            'contract_fi_firma_kompeten_lif' => 'FI FIRMA KOMPETEN LIF',
            'contract_pembersihan_kontrak' => 'PEMBERSIHAN (KONTRAK)',
            'contract_keselamatan' => 'KESELAMATAN',
            'contract_insurans' => 'INSURANS',
            'contract_jurutera_elektrik' => 'JURUTERA ELEKTRIK',
            'contract_cuci_tangki_air' => 'CUCI TANGKI AIR',
            'contract_uji_penggera_kebakaran' => 'UJI PENGGERA KEBAKARAN',
            'contract_cuci_kolam_renang' => 'CUCI KOLAM RENANG',
            'contract_sedut_pembetung' => 'SEDUT PEMBETUNG',
            'contract_potong_rumput_lanskap' => 'POTONG RUMPUT/LANSKAP',
            'contract_sistem_kad_akses' => 'SISTEM KAD AKSES',
            'contract_sistem_cctv' => 'SISTEM CCTV',
            'contract_uji_peralatan_pemadam_kebakaran' => 'UJI PERALATAN/ALAT PEMADAM KEBAKARAN',
            'contract_kutipan_sampah_pukal' => 'KUTIPAN SAMPAH PUKAL',
            'contract_kawalan_serangga' => 'KAWALAN SERANGGA',
            'repair_lif' => 'LIF',
            'repair_tangki_air' => 'TANGKI AIR',
            'repair_bumbung' => 'BUMBUNG',
            'repair_gutter' => 'GUTTER',
            'repair_rain_water_down_pipe' => 'RAIN WATER DOWN PIPE',
            'repair_pembentung' => 'PEMBENTUNG',
            'repair_perpaipan' => 'PERPAIPAN',
            'repair_wayar_bumi' => 'WAYAR BUMI',
            'repair_pendawaian_elektrik' => 'PENDAWAIAN ELEKTRIK',
            'repair_tangga_handrail' => 'TANGGA/HANDRAIL',
            'repair_jalan' => 'JALAN',
            'repair_pagar' => 'PAGAR',
            'repair_longkang' => 'LONGKANG',
            'repair_substation_tnb' => 'SUBSTATION TNB',
            'repair_alat_pemadam_kebakaran' => 'ALAT PEMADAM KEBAKARAN',
            'repair_sistem_kad_akses' => 'SISTEM KAD AKSES',
            'repair_cctv' => 'CCTV',
            'repair_pelekat_kenderaan' => 'PELEKAT KENDERAAN',
            'repair_genset' => 'GENSET',
            'vandal_lif' => 'LIF',
            'vandal_wayar_bumi' => 'WAYAR BUMI',
            'vandal_pendawaian_elektrik' => 'PENDAWAIAN ELEKTRIK',
            'vandal_pagar' => 'PAGAR',
            'vandal_substation_tnb' => 'SUBSTATION TNB',
            'vandal_peralatan_pemadam_kebakaran' => 'PERALATAN/ALAT PEMADAM KEBAKARAN',
            'vandal_sistem_kad_akses' => 'SISTEM KAD AKSES',
            'vandal_cctv' => 'CCTV',
            'vandal_genset' => 'GENSET',
            'staff_pegawal_keselamatan' => 'PENGAWAL KESELAMATAN',
            'staff_pembersihan' => 'PEMBERSIHAN',
            'staff_rencam' => 'RENCAM',
            'staff_kerani' => 'KERANI',
            'staff_juruteknik' => 'JURUTEKNIK',
            'staff_penyelia' => 'PENYELIA',
            'admin_telefon_n_internet' => 'TELEFON & INTERNET',
            'admin_peralatan' => 'PERALATAN',
            'admin_alat_tulis_perjabat' => 'ALAT TULIS PEJABAT',
            'admin_petty_cash' => 'PETTY CASH',
            'admin_sewaan_mesin_fotokopi' => 'SEWAAN MESIN FOTOKOPI',
            'admin_perkhid_sistem_ubs_n_lain' => 'PERKHIDMATAN SISTEM UBS @ LAIN-LAIN SISTEM',
            'admin_perkhid_akaun' => 'PERKHIDMATAN AKAUN',
            'admin_perkhid_audit' => 'PERKHIDMATAN AUDIT',
            'admin_caj_perundangan' => 'CAJ PERUNDANGAN',
            'admin_caj_penghantaran_n_kutipan' => 'CAJ PENGHANTARAN & KUTIPAN',
            'admin_caj_bank' => 'CAJ BANK',
            'admin_fi_ejen_pengurusan' => 'FI EJEN PENGURUSAN',
            'admin_perbelanjaan_mesyuarat' => 'PERBELANJAAN MESYUARAT',
            'admin_elaun_jmb_mc' => 'ELAUN JMB/MC',
            'admin_lain_lain_tuntutan_jmb_mc' => 'LAIN-LAIN TUNTUTAN JMB/MC',
        ]
    ]
];
