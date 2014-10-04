- DOC: Update README and docblocks.

- ADD: Make allowance for *_Bundle packages when loading configs.

- ADD: Allow multiple config classes for each config mode. Something like this
  might be in a `composer.json`:

        {
            "extra": { "aura": {
                "type": "project",
                "config": {
                    "common": [
                        "Acme\\Example\\_Config\\AuraSql",
                        "Acme\\Example\\_Config\\Twig"
                    ],
                    "dev": "Acme\\Example\\_Config\\Dev"
                }
            } }
        }

    A bigger application might want to separate configuration into several
    different classes. Normal strings still work, but arrays may be used as
    well.

- FIX: Do not execute the common config twice when the config mode is "common".

- CHG: Use new service name rules.

- CHG: Update composer to point to stable releases.
