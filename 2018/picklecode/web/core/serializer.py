import pickle
import io
import builtins

__all__ = ('PickleSerializer', )


class RestrictedUnpickler(pickle.Unpickler):
    blacklist = {'eval', 'exec', 'execfile', 'compile', 'open', 'input', '__import__', 'exit'}

    def find_class(self, module, name):
        # Only allow safe classes from builtins.
        if module == "builtins" and name not in self.blacklist:
            return getattr(builtins, name)
        # Forbid everything else.
        raise pickle.UnpicklingError("global '%s.%s' is forbidden" %
                                     (module, name))


class PickleSerializer():
    def dumps(self, obj):
        return pickle.dumps(obj)

    def loads(self, data):
        try:
            if isinstance(data, str):
                raise TypeError("Can't load pickle from unicode string")
            file = io.BytesIO(data)
            return RestrictedUnpickler(file,
                              encoding='ASCII', errors='strict').load()
        except Exception as e:
            return {}
